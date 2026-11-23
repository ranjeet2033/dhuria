@extends('layouts.frontend')

@section('title', 'Your Cart - Dhuria Ayurveda')

@section('content')

<style>
.dh-cart-wrap {
    max-width: 1250px;
    margin: 0 auto;
    padding: 30px 20px 70px;
}
.dh-cart-header {
    margin-bottom: 35px;
}
.dh-cart-kicker {
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #b89326;
    text-transform: uppercase;
    display: inline-block;
    margin-bottom: 6px;
}
.dh-cart-title {
    font-family: 'Cinzel', serif;
    font-size: 2.4rem;
    font-weight: 800;
    color: #061528;
    margin: 0;
}

.dh-cart-grid {
    display: grid;
    grid-template-columns: 1.4fr 0.9fr;
    gap: 40px;
    align-items: start;
}

/* Cart Table */
.dh-cart-table-wrap {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}
.dh-cart-head {
    display: grid;
    grid-template-columns: 90px 1.5fr 1fr 1fr;
    padding: 14px 20px;
    background: #f8fafc;
    border-bottom: 1.5px solid #e2e8f0;
    font-size: 0.75rem;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 1px;
    text-transform: uppercase;
}
.dh-cart-row {
    display: grid;
    grid-template-columns: 90px 1.5fr 1fr 1fr;
    padding: 20px;
    border-bottom: 1px solid #f1f5f9;
    align-items: center;
}
.dh-cart-row:last-child {
    border-bottom: none;
}
.dh-cart-thumb {
    width: 75px;
    height: 75px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dh-cart-thumb img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.dh-cart-prod-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #061528;
    text-decoration: none;
    display: block;
    margin-bottom: 4px;
}
.dh-cart-prod-price {
    font-size: 0.85rem;
    color: #64748b;
}
.dh-cart-qty-ctrl {
    display: inline-flex;
    align-items: center;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    overflow: hidden;
    background: #ffffff;
    margin-bottom: 6px;
}
.dh-cart-qty-btn {
    width: 30px;
    height: 32px;
    background: #f8fafc;
    border: none;
    font-weight: 800;
    cursor: pointer;
    color: #061528;
}
.dh-cart-qty-val {
    width: 36px;
    text-align: center;
    border: none;
    font-size: 0.9rem;
    font-weight: 800;
    color: #061528;
    outline: none;
}
.dh-cart-remove-link {
    font-size: 0.72rem;
    font-weight: 800;
    color: #ef4444;
    text-transform: uppercase;
    text-decoration: none;
    cursor: pointer;
    display: block;
}
.dh-cart-remove-link:hover {
    text-decoration: underline;
}
.dh-cart-total-val {
    font-size: 1.15rem;
    font-weight: 900;
    color: #061528;
}

/* Order Summary Box (matching screenshot soft blue box) */
.dh-summary-card {
    background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
    border: 1px solid #bae6fd;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 6px 20px rgba(2, 132, 199, 0.08);
}
.dh-summary-title {
    font-family: 'Cinzel', serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: #0c4a6e;
    margin: 0 0 20px 0;
    border-bottom: 1.5px solid #bae6fd;
    padding-bottom: 12px;
}
.dh-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 0.92rem;
    color: #334155;
}
.dh-summary-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1.5px dashed #0284c7;
    font-size: 1.25rem;
    font-weight: 900;
    color: #061528;
}
.dh-btn-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    background: linear-gradient(135deg, #d4af37 0%, #b89326 100%);
    color: #061528 !important;
    padding: 14px 20px;
    border-radius: 10px;
    font-size: 1.05rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-decoration: none !important;
    margin: 25px 0 20px 0;
    box-shadow: 0 6px 18px rgba(212, 175, 55, 0.35);
    transition: all 0.25s;
    border: none;
}
.dh-btn-checkout:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff !important;
    transform: translateY(-2px);
}

@media (max-width: 992px) {
    .dh-cart-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .dh-cart-head { display: none; }
    .dh-cart-row { grid-template-columns: 80px 1fr; gap: 15px; }
}
</style>

<div class="dh-cart-wrap">
    <div class="dh-cart-header">
        <span class="dh-cart-kicker">SHOPPING BAG</span>
        <h1 class="dh-cart-title">Your Cart</h1>
    </div>

    @if(empty($cart) || count($cart) == 0)
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:18px; padding:60px 20px; text-align:center; max-width:650px; margin:0 auto;">
            <i class="bi bi-bag-x" style="font-size:3.5rem; color:#cbd5e1; display:block; margin-bottom:15px;"></i>
            <h3 style="font-family:'Cinzel',serif; font-size:1.5rem; color:#061528; margin-bottom:8px;">Your Shopping Bag is Empty</h3>
            <p style="color:#64748b; font-size:0.95rem; margin-bottom:25px;">Discover our authentic Ayurvedic kits, powders, and detox formulations.</p>
            <a href="{{ route('shop') }}" style="background:#061528; color:#d4af37; border:1px solid #d4af37; padding:12px 30px; border-radius:30px; font-weight:800; text-transform:uppercase; text-decoration:none;">EXPLORE FORMULATIONS</a>
        </div>
    @else
        @php
            $subtotal = 0;
        @endphp
        <div class="dh-cart-grid">
            <!-- Left: Items Table -->
            <div class="dh-cart-table-wrap">
                <div class="dh-cart-head">
                    <div>Product</div>
                    <div>Details</div>
                    <div>Quantity</div>
                    <div>Total</div>
                </div>

                @foreach($cart as $id => $item)
                    @php
                        $liveProduct = \App\Models\Product::find($id);
                        $itemName = $liveProduct ? $liveProduct->name : $item['name'];
                        $itemPrice = $liveProduct ? ($liveProduct->sale_price ?? $liveProduct->price) : $item['price'];
                        $itemImage = $liveProduct ? $liveProduct->primary_image_url : ($item['image'] ?? 'images/liver_detox_product.jpg');
                        $lineTotal = $itemPrice * $item['quantity'];
                        $subtotal += $lineTotal;
                    @endphp
                    <div class="dh-cart-row">
                        <div class="dh-cart-thumb">
                            <img src="{{ str_starts_with($itemImage, 'http') ? $itemImage : asset($itemImage) }}" alt="{{ $itemName }}">
                        </div>

                        <div>
                            <a href="/product/{{ $liveProduct->slug ?? '' }}" class="dh-cart-prod-title">{{ $itemName }}</a>
                            <span class="dh-cart-prod-price">₹{{ number_format($itemPrice, 2) }}</span>
                        </div>

                        <div>
                            <div class="dh-cart-qty-ctrl">
                                <button type="button" class="dh-cart-qty-btn" onclick="updateCartQuantity('{{ $id }}', {{ $item['quantity'] - 1 }})">-</button>
                                <input type="text" value="{{ $item['quantity'] }}" readonly class="dh-cart-qty-val">
                                <button type="button" class="dh-cart-qty-btn" onclick="updateCartQuantity('{{ $id }}', {{ $item['quantity'] + 1 }})">+</button>
                            </div>
                            <span class="dh-cart-remove-link" onclick="removeCartItem('{{ $id }}')">REMOVE</span>
                        </div>

                        <div class="dh-cart-total-val">
                            ₹{{ number_format($lineTotal, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right: Order Summary Card -->
            <div class="dh-summary-card">
                <h3 class="dh-summary-title">Order Summary</h3>

                <div class="dh-summary-row">
                    <span>Subtotal</span>
                    <strong style="color:#061528;">₹{{ number_format($subtotal, 2) }}</strong>
                </div>

                <div class="dh-summary-row">
                    <span>Shipping</span>
                    <strong style="color:#10b981;">FREE</strong>
                </div>

                <div class="dh-summary-total-row">
                    <span>Total</span>
                    <span style="color:#061528;">₹{{ number_format($subtotal, 2) }}</span>
                </div>

                <a href="{{ route('checkout.index') }}" class="dh-btn-checkout">
                    <span>CHECKOUT</span> <i class="bi bi-arrow-right"></i>
                </a>

                <div style="font-size:0.8rem; color:#475569; display:flex; flex-direction:column; gap:6px;">
                    <div><i class="bi bi-shield-check text-green-600"></i> Secure encrypted checkout</div>
                    <div><i class="bi bi-truck text-yellow-600"></i> Free express shipping across India</div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function updateCartQuantity(productId, newQty) {
    if (newQty < 1) {
        removeCartItem(productId);
        return;
    }
    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId, quantity: newQty })
    })
    .then(r => r.json())
    .then(data => {
        window.location.reload();
    });
}

function removeCartItem(productId) {
    if(!confirm('Remove this product from your cart?')) return;
    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        window.location.reload();
    });
}
</script>

@endsection
