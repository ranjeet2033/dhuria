<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Get cart contents
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('frontend.cart', compact('cart'));
    }

    // Add to cart
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Quantity must be at least 1.'], 400);
        }

        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        $cart = session()->get('cart', []);

        // Check stock availability
        if ($product->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this product is currently out of stock.'
            ], 400);
        }

        $currentQty = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->quantity) {
            $available = max(0, $product->quantity - $currentQty);
            if ($available <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have the maximum available stock (' . $product->quantity . ') in your cart.'
                ], 400);
            }
            return response()->json([
                'success' => false,
                'message' => 'Cannot add ' . $quantity . ' items. Only ' . $available . ' more unit(s) available in stock.'
            ], 400);
        }

        // Prepare image
        $image = $product->primary_image_url;

        $basePrice = (float)($product->sale_price ?? $product->price);
        $effectiveUnitPrice = $basePrice;
        $packName = null;

        // Check if a pack size is selected or matching
        $packSizes = $product->formatted_pack_sizes;
        $packIndex = $request->input('pack_index', null);

        if ($packIndex !== null && isset($packSizes[$packIndex])) {
            $selectedPack = $packSizes[$packIndex];
            $packQty = (int)($selectedPack['quantity'] ?? 1);
            $packName = $selectedPack['name'] ?? null;
            if (!empty($selectedPack['custom_price']) && (float)$selectedPack['custom_price'] > 0) {
                $effectiveUnitPrice = round((float)$selectedPack['custom_price'] / max(1, $packQty), 2);
            } elseif (!empty($selectedPack['multiplier'])) {
                $effectiveUnitPrice = round(($basePrice * (float)$selectedPack['multiplier']) / max(1, $packQty), 2);
            }
        } elseif (is_array($packSizes) && count($packSizes) > 0) {
            // Find if current quantity directly matches any pack option
            foreach ($packSizes as $p) {
                if ((int)($p['quantity'] ?? 0) === $quantity) {
                    $packName = $p['name'] ?? null;
                    if (!empty($p['custom_price']) && (float)$p['custom_price'] > 0) {
                        $effectiveUnitPrice = round((float)$p['custom_price'] / max(1, $quantity), 2);
                    } elseif (!empty($p['multiplier'])) {
                        $effectiveUnitPrice = round(($basePrice * (float)$p['multiplier']) / max(1, $quantity), 2);
                    }
                    break;
                }
            }
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            $cart[$productId]['price'] = $effectiveUnitPrice;
            if ($packName) {
                $cart[$productId]['pack_name'] = $packName;
            }
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $effectiveUnitPrice,
                'image' => $image,
                'slug' => $product->slug,
                'pack_name' => $packName,
            ];
        }

        session()->put('cart', $cart);

        $totalCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $totalCount,
            'cart' => $cart
        ]);
    }

    // Update cart item quantity
    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity');

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            if ($quantity > 0 && $product && $quantity > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update quantity. Only ' . $product->quantity . ' unit(s) available in stock for ' . $product->name . '.'
                ], 400);
            }

            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
        }

        $totalCount = array_sum(array_column($cart, 'quantity'));
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart_count' => $totalCount,
            'total_price' => $totalPrice,
            'cart' => $cart
        ]);
    }

    // Remove from cart
    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        $totalCount = array_sum(array_column($cart, 'quantity'));
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart successfully!',
            'cart_count' => $totalCount,
            'total_price' => $totalPrice,
            'cart' => $cart
        ]);
    }

    // Get cart item count
    public function count()
    {
        $cart = session()->get('cart', []);
        $totalCount = array_sum(array_column($cart, 'quantity'));
        return response()->json(['cart_count' => $totalCount]);
    }

    public function applyCoupon(Request $request)
    {
        $code = trim($request->input('code', $request->input('coupon_code', '')));

        if (empty($code)) {
            $msg = 'Please enter a coupon code.';
            if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $coupon = \App\Models\Coupon::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            $msg = 'Invalid or inactive coupon code.';
            if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        if ($coupon->expiry_date && $coupon->expiry_date < now()->format('Y-m-d')) {
            $msg = 'This coupon has expired.';
            if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        if ($coupon->usage_limit !== null && $coupon->used >= $coupon->usage_limit) {
            $msg = 'This coupon has reached its maximum usage limit.';
            if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        // Calculate cart subtotal
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $discount = 0;
        if ($coupon->type === 'fixed') {
            $discount = min($subtotal, (float)$coupon->value);
        } else {
            $discount = round($subtotal * ((float)$coupon->value / 100), 2);
        }
        $newTotal = max(0, $subtotal - $discount);

        // Save coupon details in session
        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float)$coupon->value,
            'discount' => $discount,
        ]);

        $msg = 'Coupon applied successfully!';
        if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'coupon' => $coupon->code,
                'discount' => $discount,
                'new_total' => $newTotal
            ]);
        }

        return back()->with('success', $msg);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('coupon');
        $msg = 'Coupon removed successfully.';

        if ($request->expectsJson() || $request->isJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }
}
