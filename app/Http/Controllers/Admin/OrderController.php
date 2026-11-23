<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            if ($request->status === 'return_requested') {
                $query->where('return_status', 'pending');
            } elseif ($request->status === 'returned') {
                $query->where(function($q) {
                    $q->where('status', 'returned')
                      ->orWhere('return_status', 'approved');
                });
            } elseif ($request->status === 'return_rejected') {
                $query->where(function($q) {
                    $q->where('status', 'return_rejected')
                      ->orWhere('return_status', 'rejected');
                });
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_name', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_email', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'              => 'required|string|in:pending,processing,shipped,completed,cancelled,failed,returned,return_rejected',
            'payment_status'      => 'required|string|in:pending,completed,failed,refunded,refund_pending,cancelled',
            'ups_tracking_number' => 'nullable|string|max:100',
            'return_status'       => 'nullable|string|in:pending,approved,rejected',
        ]);

        $status = $request->status;
        $returnStatus = $request->filled('return_status') ? $request->return_status : $order->return_status;
        $paymentStatus = $request->payment_status;

        // Auto-sync order status with return status updates
        if ($request->filled('return_status')) {
            if ($request->return_status === 'approved') {
                $status = 'returned';
                if ($order->payment_method === 'razorpay' && $order->payment_status === 'completed' && $order->razorpay_payment_id && empty($order->refund_id)) {
                    $key = config('services.razorpay.key') ?: \App\Models\Setting::get('razorpay_key');
                    $secret = config('services.razorpay.secret') ?: \App\Models\Setting::get('razorpay_secret');
                    if (!empty($key) && !empty($secret)) {
                        try {
                            $api = new \Razorpay\Api\Api($key, $secret);
                            $payment = $api->payment->fetch($order->razorpay_payment_id);
                            $refund = $payment->refund([
                                'amount' => (int) round($order->total_amount * 100),
                                'notes'  => ['order_number' => $order->order_number, 'reason' => 'Return Approved by Admin']
                            ]);
                            $order->refund_id = $refund->id;
                            $order->refund_amount = $order->total_amount;
                            $paymentStatus = 'refunded';
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Admin return refund error: ' . $e->getMessage());
                            $paymentStatus = 'refund_pending';
                        }
                    }
                }
            } elseif ($request->return_status === 'rejected') {
                $status = 'return_rejected';
            }
        }

        $order->update([
            'status'              => $status,
            'payment_status'      => $paymentStatus,
            'ups_tracking_number' => $request->ups_tracking_number,
            'return_status'       => $returnStatus,
        ]);

        return redirect()->back()->with('success', 'Order updated successfully.');
    }

    public function createShiprocketShipment(Order $order, \App\Services\ShiprocketService $shiprocket)
    {
        try {
            $result = $shiprocket->createShipment($order);
            return redirect()->back()->with('success', 'Shiprocket shipment created successfully! Shipment ID: ' . ($result['shipment_id'] ?? ''));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Shiprocket Error: ' . $e->getMessage());
        }
    }

    public function trackShiprocketShipment(Order $order, \App\Services\ShiprocketService $shiprocket)
    {
        try {
            if ($order->shiprocket_awb_code) {
                $tracking = $shiprocket->trackByAwb($order->shiprocket_awb_code);
            } elseif ($order->shiprocket_order_id) {
                $tracking = $shiprocket->trackByOrderId($order->shiprocket_order_id);
            } else {
                return redirect()->back()->with('error', 'No Shiprocket tracking ID or AWB code found on this order.');
            }

            if ($tracking) {
                return redirect()->back()->with('success', 'Shipment Status: ' . json_encode($tracking));
            }
            return redirect()->back()->with('warning', 'No tracking updates available yet.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tracking Error: ' . $e->getMessage());
        }
    }
}
