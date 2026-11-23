<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerOrderController extends Controller
{
    // Display all customer orders
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())->with(['items.product']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', '%' . $search . '%');
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('frontend.orders.index', compact('orders'));
    }

    // Submit return request
    public function requestReturn(Request $request, Order $order)
    {
        // Ensure order belongs to logged in user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Return is only allowed for delivered/completed orders that haven't been returned yet
        if (!in_array($order->status, ['delivered', 'completed'])) {
            return redirect()->back()->with('error', 'Return requests are only permitted for delivered orders.')->with('tab', 'orders');
        }

        if (!empty($order->return_status)) {
            return redirect()->back()->with('error', 'A return request for this order is already ' . ucfirst($order->return_status) . '.')->with('tab', 'orders');
        }

        // Check 7-day return window from delivery
        $deliveredAt = $order->updated_at ?: $order->created_at;
        if ($deliveredAt->diffInDays(now()) > 7) {
            return redirect()->back()->with('error', 'The standard 7-day return period for this order has expired. Please contact support if you require assistance.')->with('tab', 'orders');
        }

        $validated = $request->validate([
            'return_type'            => 'required|string|in:return,replace',
            'return_reason'          => 'required|string|max:255',
            'return_comments'        => 'nullable|string|max:1000',
            'refund_account_details' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'return_type'            => $validated['return_type'],
            'return_reason'          => $validated['return_reason'],
            'return_comments'        => $validated['return_comments'] ?? null,
            'refund_account_details' => $validated['return_type'] === 'return' ? ($validated['refund_account_details'] ?? null) : null,
            'return_status'          => 'pending', // Pending admin approval
            'notes'                  => trim(($order->notes ?? '') . "\nReturn requested by customer on " . now()->format('d M Y, h:i A') . " (" . ucfirst($validated['return_type']) . "). Reason: " . $validated['return_reason']),
        ]);

        $message = $validated['return_type'] === 'replace'
            ? 'Your replacement request for Order #' . ($order->order_number ?? $order->id) . ' has been submitted. Our team will review and arrange reverse pickup.'
            : 'Your return request for Order #' . ($order->order_number ?? $order->id) . ' has been submitted. Our team will review and approve refund processing within 24-48 hours.';

        return redirect()->back()->with('success', $message)->with('tab', 'orders');
    }

    // Cancel order
    public function cancelOrder(Request $request, Order $order)
    {
        // Ensure order belongs to logged in user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Prevent repeated cancellations
        if ($order->status === 'cancelled') {
            return redirect()->back()->with('error', 'This order is already cancelled.')->with('tab', 'orders');
        }

        // Only allow cancellation if order is pending or processing (before shipment)
        if (!in_array($order->status, ['pending', 'processing'])) {
            if (in_array($order->status, ['shipped'])) {
                return redirect()->back()->with('error', 'Order has already been shipped and handed over to courier. It cannot be cancelled now. You may request a return once delivered.')->with('tab', 'orders');
            }
            if (in_array($order->status, ['delivered', 'completed'])) {
                return redirect()->back()->with('error', 'Order has already been delivered. Please use the Return Product option instead.')->with('tab', 'orders');
            }
            return redirect()->back()->with('error', 'This order is not eligible for cancellation.')->with('tab', 'orders');
        }

        $validated = $request->validate([
            'cancellation_reason'    => 'required|string|max:255',
            'cancellation_comments'  => 'nullable|string|max:1000',
            'refund_account_details' => 'nullable|string|max:1000',
        ]);

        // 1. Restock products in inventory
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('quantity', $item->quantity);
            }
        }

        // 2. Handle Payment & Refund for Prepaid Orders
        $refundId = null;
        $refundAmount = null;
        $paymentStatus = $order->payment_status;
        $refundNote = '';

        if ($order->payment_method === 'razorpay' && $order->payment_status === 'completed' && $order->razorpay_payment_id) {
            $key = config('services.razorpay.key') ?: Setting::get('razorpay_key');
            $secret = config('services.razorpay.secret') ?: Setting::get('razorpay_secret');

            if (!empty($key) && !empty($secret)) {
                try {
                    $api = new \Razorpay\Api\Api($key, $secret);
                    $payment = $api->payment->fetch($order->razorpay_payment_id);
                    $refund = $payment->refund([
                        'amount' => (int) round($order->total_amount * 100), // in paise
                        'notes' => [
                            'order_number' => $order->order_number,
                            'reason'       => $validated['cancellation_reason'],
                        ]
                    ]);
                    $refundId = $refund->id;
                    $refundAmount = $order->total_amount;
                    $paymentStatus = 'refunded';
                    $refundNote = " | Razorpay Refund Initiated (ID: {$refundId}, Amount: ₹{$refundAmount})";
                } catch (\Exception $e) {
                    Log::error('Razorpay refund API error for order ' . $order->order_number . ': ' . $e->getMessage());
                    $paymentStatus = 'refund_pending';
                    $refundNote = " | Prepaid Razorpay refund pending manual verification (" . $e->getMessage() . ")";
                }
            } else {
                $paymentStatus = 'refund_pending';
                $refundNote = " | Prepaid order marked for refund (Razorpay credentials pending setup)";
            }
        } elseif ($order->payment_method === 'cod') {
            $paymentStatus = 'cancelled';
        }

        // 3. Update Order in DB
        $reasonFull = $validated['cancellation_reason'] . (!empty($validated['cancellation_comments']) ? ' - ' . $validated['cancellation_comments'] : '');

        $order->update([
            'status'                 => 'cancelled',
            'cancellation_reason'    => $reasonFull,
            'cancelled_at'           => now(),
            'payment_status'         => $paymentStatus,
            'refund_id'              => $refundId,
            'refund_amount'          => $refundAmount,
            'refund_account_details' => $validated['refund_account_details'] ?? null,
            'notes'                  => trim(($order->notes ?? '') . "\nCancelled by customer on " . now()->format('d M Y, h:i A') . ". Reason: " . $reasonFull . $refundNote),
        ]);

        $successMessage = 'Order #' . ($order->order_number ?? $order->id) . ' has been cancelled successfully.';
        if ($paymentStatus === 'refunded') {
            $successMessage .= ' A refund of ₹' . number_format($order->total_amount, 2) . ' has been initiated to your original payment method (Ref: ' . $refundId . ').';
        } elseif ($paymentStatus === 'refund_pending') {
            $successMessage .= ' Since this was a prepaid order, our accounts team will verify and process your refund within 3 business days.';
        }

        return redirect()->back()->with('success', $successMessage)->with('tab', 'orders');
    }
}
