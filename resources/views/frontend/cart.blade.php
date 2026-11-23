@extends('layouts.frontend')

@section('title', 'Your Shopping Cart - Dhuria Ayurveda')

@section('content')

<style>
/* ============================================================
   DHURIA AYURVEDA LUXURY CART STYLES
   Designed for Desktop, Tablet, and Mobile (320px - 1600px+)
   ============================================================ */

.dh-cart-page {
    background-color: #f8fafc;
    min-height: 80vh;
    padding: 30px 16px 80px;
}

.dh-cart-container {
    max-width: 1240px;
    margin: 0 auto;
}

/* Header */
.dh-cart-header {
    margin-bottom: 24px;
}
.dh-cart-kicker {
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #b89326;
    text-transform: uppercase;
    display: inline-block;
    margin-bottom: 4px;
}
.dh-cart-title-row {
    display: flex;
    align-items: baseline;
    gap: 12px;
    flex-wrap: wrap;
}
.dh-cart-title {
    font-family: 'Cinzel', serif;
    font-size: 2.2rem;
    font-weight: 800;
    color: #081a30;
    margin: 0;
    line-height: 1.2;
}
.dh-cart-count-badge {
    background: #081a30;
    color: #d4af37;
    font-size: 0.8rem;
    font-weight: 800;
    padding: 3px 12px;
    border-radius: 20px;
    letter-spacing: 0.5px;
}

/* Free Shipping Meter Banner */
.dh-shipping-meter {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #10b981;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
}
.dh-shipping-meter i {
    font-size: 1.4rem;
    color: #10b981;
    flex-shrink: 0;
}
.dh-shipping-meter-text {
    font-size: 0.88rem;
    color: #1e293b;
    font-weight: 600;
}
.dh-shipping-meter-text strong {
    color: #081a30;
}

/* Cart Grid */
.dh-cart-layout {
    display: grid;
    grid-template-columns: 1fr 390px;
    gap: 32px;
    align-items: start;
}

/* Cart Items Column */
.dh-cart-items-wrap {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* Single Cart Item Card */
.dh-cart-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 3px 12px rgba(8, 26, 48, 0.04);
    transition: all 0.2s ease;
}
.dh-cart-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 18px rgba(8, 26, 48, 0.06);
}

.dh-cart-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 12px;
    margin-bottom: 14px;
    border-bottom: 1px solid #f1f5f9;
}
.dh-cart-tag-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #047857;
    background: #ecfdf5;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid #a7f3d0;
}
.dh-cart-delete-btn {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
}
.dh-cart-delete-btn:hover {
    color: #ef4444;
    background: #fef2f2;
}

/* Card Main Flex */
.dh-cart-card-main {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}
.dh-cart-img-box {
    width: 90px;
    height: 90px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 6px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.dh-cart-img-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.25s ease;
}
.dh-cart-img-box:hover img {
    transform: scale(1.05);
}

.dh-cart-details {
    flex: 1;
    min-width: 0;
}
.dh-cart-name {
    font-size: 1.05rem;
    font-weight: 800;
    color: #081a30;
    text-decoration: none;
    line-height: 1.35;
    display: block;
    margin-bottom: 6px;
    transition: color 0.2s ease;
}
.dh-cart-name:hover {
    color: #d4af37;
}

.dh-cart-pack-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #1e3a8a;
    background: #eff6ff;
    padding: 3px 9px;
    border-radius: 6px;
    border: 1px solid #bfdbfe;
    margin-bottom: 8px;
}

.dh-cart-unit-price-row {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}
.dh-cart-cur-unit {
    font-size: 0.95rem;
    font-weight: 800;
    color: #081a30;
}
.dh-cart-old-unit {
    font-size: 0.85rem;
    color: #94a3b8;
    text-decoration: line-through;
}
.dh-cart-save-unit {
    font-size: 0.72rem;
    font-weight: 800;
    color: #10b981;
    background: #ecfdf5;
    padding: 2px 7px;
    border-radius: 4px;
}

/* Bottom Row inside card: Stepper & Total */
.dh-cart-bottom-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 14px;
    border-top: 1px dashed #f1f5f9;
    flex-wrap: wrap;
    gap: 12px;
}
.dh-stepper-wrap {
    display: inline-flex;
    align-items: center;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    background: #ffffff;
    overflow: hidden;
}
.dh-stepper-btn {
    width: 34px;
    height: 34px;
    background: #f8fafc;
    border: none;
    color: #081a30;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}
.dh-stepper-btn:hover {
    background: #e2e8f0;
    color: #d4af37;
}
.dh-stepper-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}
.dh-stepper-val {
    width: 44px;
    height: 34px;
    text-align: center;
    border: none;
    border-left: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    font-size: 0.95rem;
    font-weight: 800;
    color: #081a30;
    background: #ffffff;
    outline: none;
}

.dh-cart-line-total {
    text-align: right;
}
.dh-cart-line-total-lbl {
    font-size: 0.72rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.dh-cart-line-total-val {
    font-size: 1.3rem;
    font-weight: 900;
    color: #081a30;
    font-feature-settings: "tnum";
}

/* ============================================================
   RIGHT COLUMN: LUXURY ORDER SUMMARY CARD
   ============================================================ */
.dh-summary-card {
    background: linear-gradient(145deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 1px solid #bae6fd;
    border-radius: 20px;
    padding: 26px 24px;
    box-shadow: 0 8px 24px rgba(2, 132, 199, 0.08);
    position: sticky;
    top: 90px;
}

.dh-summary-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1.5px solid #bae6fd;
}
.dh-summary-title {
    font-family: 'Cinzel', serif;
    font-size: 1.35rem;
    font-weight: 800;
    color: #0c4a6e;
    margin: 0;
}
.dh-summary-secure-tag {
    font-size: 0.72rem;
    font-weight: 700;
    color: #0284c7;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Coupon Box */
.dh-coupon-box {
    margin-bottom: 20px;
    background: #ffffff;
    border: 1px solid #bae6fd;
    border-radius: 12px;
    padding: 12px;
}
.dh-coupon-form {
    display: flex;
    gap: 8px;
}
.dh-coupon-input {
    flex: 1;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #081a30;
    outline: none;
    letter-spacing: 0.5px;
}
.dh-coupon-input:focus {
    border-color: #d4af37;
}
.dh-coupon-btn {
    background: #081a30;
    color: #d4af37;
    border: none;
    border-radius: 8px;
    padding: 9px 16px;
    font-size: 0.82rem;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.dh-coupon-btn:hover {
    background: #112d52;
}

.dh-coupon-active {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.82rem;
    color: #047857;
    font-weight: 700;
}
.dh-coupon-remove-btn {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    font-size: 0.78rem;
    font-weight: 700;
    text-decoration: underline;
}

/* Summary Rows */
.dh-sum-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 0.92rem;
    color: #334155;
}
.dh-sum-val {
    font-weight: 800;
    color: #081a30;
}
.dh-sum-free {
    color: #10b981;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.dh-sum-discount {
    color: #10b981;
    font-weight: 800;
}

.dh-sum-total-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1.5px dashed #0284c7;
}
.dh-sum-total-lbl {
    font-size: 1.1rem;
    font-weight: 800;
    color: #081a30;
}
.dh-sum-total-val {
    font-size: 1.6rem;
    font-weight: 900;
    color: #081a30;
}
.dh-sum-tax-note {
    font-size: 0.75rem;
    color: #64748b;
    text-align: right;
    margin-top: 2px;
    margin-bottom: 20px;
}

/* Checkout Button */
.dh-btn-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    background: linear-gradient(135deg, #d4af37 0%, #b89326 100%);
    color: #081a30 !important;
    padding: 15px 24px;
    border-radius: 12px;
    font-size: 1.05rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-decoration: none !important;
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
    transition: all 0.25s ease;
    border: none;
    cursor: pointer;
}
.dh-btn-checkout:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5);
}

.dh-continue-shop-link {
    display: block;
    text-align: center;
    margin-top: 14px;
    font-size: 0.85rem;
    font-weight: 700;
    color: #0284c7;
    text-decoration: none;
}
.dh-continue-shop-link:hover {
    text-decoration: underline;
}

/* Trust Badges */
.dh-trust-list {
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px solid #bae6fd;
    display: flex;
    flex-direction: column;
    gap: 8px;
    font-size: 0.78rem;
    color: #475569;
}
.dh-trust-item {
    display: flex;
    align-items: center;
    gap: 8px;
}
.dh-trust-item i {
    color: #0284c7;
    font-size: 0.95rem;
    flex-shrink: 0;
}

/* Payment Icons Row */
.dh-pay-icons-row {
    margin-top: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    color: #64748b;
    font-size: 1.25rem;
}

/* Empty State */
.dh-empty-cart-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 70px 24px;
    text-align: center;
    max-width: 600px;
    margin: 30px auto;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
}
.dh-empty-icon {
    font-size: 4rem;
    color: #cbd5e1;
    display: block;
    margin-bottom: 16px;
}
.dh-empty-title {
    font-family: 'Cinzel', serif;
    font-size: 1.6rem;
    color: #081a30;
    font-weight: 800;
    margin-bottom: 8px;
}
.dh-empty-desc {
    color: #64748b;
    font-size: 0.95rem;
    margin-bottom: 28px;
    line-height: 1.6;
}
.dh-empty-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #081a30;
    color: #d4af37 !important;
    border: 1px solid #d4af37;
    padding: 13px 32px;
    border-radius: 30px;
    font-size: 0.92rem;
    font-weight: 800;
    text-transform: uppercase;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 4px 15px rgba(8, 26, 48, 0.15);
}
.dh-empty-btn:hover {
    background: #112d52;
    transform: translateY(-2px);
}

/* ============================================================
   RESPONSIVE DESIGN (320px to 992px)
   ============================================================ */
@media (max-width: 992px) {
    .dh-cart-layout {
        grid-template-columns: 1fr;
    }
    .dh-summary-card {
        position: static;
        margin-top: 10px;
    }
}

@media (max-width: 576px) {
    .dh-cart-page {
        padding: 20px 12px 60px;
    }
    .dh-cart-title {
        font-size: 1.8rem;
    }
    .dh-cart-card {
        padding: 16px 14px;
        border-radius: 14px;
    }
    .dh-cart-card-main {
        gap: 12px;
    }
    .dh-cart-img-box {
        width: 72px;
        height: 72px;
        border-radius: 10px;
    }
    .dh-cart-name {
        font-size: 0.95rem;
    }
    .dh-cart-bottom-row {
        padding-top: 12px;
    }
    .dh-stepper-btn {
        width: 30px;
        height: 30px;
        font-size: 1rem;
    }
    .dh-stepper-val {
        width: 38px;
        height: 30px;
        font-size: 0.88rem;
    }
    .dh-cart-line-total-val {
        font-size: 1.15rem;
    }
    .dh-summary-card {
        padding: 20px 16px;
        border-radius: 16px;
    }
    .dh-summary-title {
        font-size: 1.2rem;
    }
    .dh-btn-checkout {
        padding: 14px 20px;
        font-size: 0.98rem;
    }
}
</style>

<div class="dh-cart-page">
    <div class="dh-cart-container">

        <!-- Cart Header -->
        <div class="dh-cart-header">
            <span class="dh-cart-kicker">SHOPPING BAG</span>
            <div class="dh-cart-title-row">
                <h1 class="dh-cart-title">Your Cart</h1>
                @if(!empty($cart) && count($cart) > 0)
                    @php $itemCount = array_sum(array_column($cart, 'quantity')); @endphp
                    <span class="dh-cart-count-badge">{{ $itemCount }} {{ $itemCount === 1 ? 'ITEM' : 'ITEMS' }}</span>
                @endif
            </div>
        </div>

        @if(empty($cart) || count($cart) == 0)
            <!-- Empty State -->
            <div class="dh-empty-cart-box">
                <i class="bi bi-bag-x dh-empty-icon"></i>
                <h2 class="dh-empty-title">Your Shopping Bag is Empty</h2>
                <p class="dh-empty-desc">
                    Your cart is currently empty. Explore our authentic classical Ayurvedic formulations, targeted care kits, and daily wellness powders crafted for holistic balance.
                </p>
                <a href="{{ route('shop') }}" class="dh-empty-btn">
                    <i class="bi bi-compass"></i> EXPLORE FORMULATIONS
                </a>
            </div>
        @else
            @php
                $subtotal = 0;
                $originalTotal = 0;
                $coupon = session('coupon');
            @endphp

            <!-- Free Shipping Qualified Banner -->
            <div class="dh-shipping-meter">
                <i class="bi bi-patch-check-fill"></i>
                <div class="dh-shipping-meter-text">
                    <strong>Free Express Shipping Unlocked!</strong> Your order qualifies for 100% free delivery across all pin-codes in India.
                </div>
            </div>

            <!-- Two-Column Layout -->
            <div class="dh-cart-layout">

                <!-- Left Column: Items List -->
                <div class="dh-cart-items-wrap">
                    @foreach($cart as $id => $item)
                        @php
                            $liveProduct = \App\Models\Product::find($id);
                            $itemName = $liveProduct ? $liveProduct->name : ($item['name'] ?? 'Ayurvedic Product');
                            $itemSlug = $liveProduct ? $liveProduct->slug : ($item['slug'] ?? '');
                            $productUrl = $itemSlug ? route('product.show', $itemSlug) : '#';
                            
                            // Determine item unit price: prefer stored unit price from pack selection, fallback to sale_price or price
                            $unitPrice = !empty($item['price']) && (float)$item['price'] > 0 
                                ? (float)$item['price'] 
                                : (float)($liveProduct ? ($liveProduct->sale_price ?? $liveProduct->price) : 0);
                            
                            $regularUnitPrice = $liveProduct ? (float)$liveProduct->price : $unitPrice;
                            $quantity = max(1, (int)($item['quantity'] ?? 1));
                            $lineTotal = $unitPrice * $quantity;
                            $subtotal += $lineTotal;
                            $originalTotal += ($regularUnitPrice * $quantity);

                            // Image handling
                            $itemImage = $liveProduct ? $liveProduct->primary_image_url : ($item['image'] ?? 'images/card_diabetes_pro_hd.jpg');
                            $imageSrc = (str_starts_with($itemImage, 'http://') || str_starts_with($itemImage, 'https://'))
                                ? $itemImage 
                                : asset($itemImage);
                            
                            $packName = $item['pack_name'] ?? null;
                            $savings = max(0, ($regularUnitPrice * $quantity) - $lineTotal);
                        @endphp

                        <div class="dh-cart-card" id="cartCard-{{ $id }}">
                            <!-- Top row: Authenticity Badge + Remove Button -->
                            <div class="dh-cart-card-top">
                                <span class="dh-cart-tag-pill">
                                    <i class="bi bi-shield-check"></i> 100% Classical Pure Ayurveda
                                </span>
                                <button type="button" class="dh-cart-delete-btn" onclick="removeCartItem('{{ $id }}', '{{ addslashes($itemName) }}')" title="Remove item">
                                    <i class="bi bi-trash3"></i> <span>Remove</span>
                                </button>
                            </div>

                            <!-- Main row: Thumbnail + Details -->
                            <div class="dh-cart-card-main">
                                <a href="{{ $productUrl }}" class="dh-cart-img-box" title="{{ $itemName }}">
                                    <img src="{{ $imageSrc }}" alt="{{ $itemName }}" loading="lazy">
                                </a>

                                <div class="dh-cart-details">
                                    <a href="{{ $productUrl }}" class="dh-cart-name">{{ $itemName }}</a>

                                    @if(!empty($packName))
                                        <div class="dh-cart-pack-badge">
                                            <i class="bi bi-box-seam"></i> {{ $packName }}
                                        </div>
                                    @endif

                                    <div class="dh-cart-unit-price-row">
                                        <span class="dh-cart-cur-unit">&#8377;{{ number_format($unitPrice, 2) }}</span>
                                        @if($regularUnitPrice > $unitPrice)
                                            <span class="dh-cart-old-unit">&#8377;{{ number_format($regularUnitPrice, 2) }}</span>
                                            <span class="dh-cart-save-unit">Save &#8377;{{ number_format($regularUnitPrice - $unitPrice, 0) }}/unit</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom row: Tactile Stepper + Line Subtotal -->
                            <div class="dh-cart-bottom-row">
                                <div>
                                    <div class="dh-stepper-wrap">
                                        <button type="button" class="dh-stepper-btn" onclick="changeQty('{{ $id }}', -1)" title="Decrease Quantity">-</button>
                                        <input type="text" class="dh-stepper-val" value="{{ $quantity }}" readonly aria-label="Quantity">
                                        <button type="button" class="dh-stepper-btn" onclick="changeQty('{{ $id }}', 1)" title="Increase Quantity">+</button>
                                    </div>
                                </div>

                                <div class="dh-cart-line-total">
                                    <div class="dh-cart-line-total-lbl">Subtotal</div>
                                    <div class="dh-cart-line-total-val">&#8377;{{ number_format($lineTotal, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Column: Sticky Summary Box -->
                <div class="dh-summary-card">
                    <div class="dh-summary-head">
                        <h3 class="dh-summary-title">Order Summary</h3>
                        <span class="dh-summary-secure-tag"><i class="bi bi-lock-fill"></i> Verified SSL</span>
                    </div>

                    <!-- Coupon Code Input / Display -->
                    <div class="dh-coupon-box">
                        @if($coupon && !empty($coupon['code']))
                            <div class="dh-coupon-active">
                                <div>
                                    <i class="bi bi-tag-fill"></i> Code <strong>{{ $coupon['code'] }}</strong> Applied!
                                </div>
                                <button type="button" class="dh-coupon-remove-btn" onclick="removeCoupon()">Remove</button>
                            </div>
                        @else
                            <form id="couponForm" onsubmit="applyCoupon(event)" class="dh-coupon-form">
                                <input type="text" id="couponCodeInput" class="dh-coupon-input" placeholder="Coupon Code" required autocomplete="off">
                                <button type="submit" class="dh-coupon-btn" id="couponBtn">APPLY</button>
                            </form>
                            <div id="couponMsg" style="display:none; font-size:0.75rem; margin-top:6px; font-weight:700;"></div>
                        @endif
                    </div>

                    <!-- Item Breakdown -->
                    <div class="dh-sum-row">
                        <span>Items Subtotal</span>
                        <span class="dh-sum-val">&#8377;{{ number_format($subtotal, 2) }}</span>
                    </div>

                    @php
                        $totalSavings = max(0, $originalTotal - $subtotal);
                        $couponDiscount = 0;
                        if ($coupon && !empty($coupon['discount'])) {
                            $couponDiscount = min($subtotal, (float)$coupon['discount']);
                        }
                        $finalTotal = max(0, $subtotal - $couponDiscount);
                    @endphp

                    @if($totalSavings > 0)
                        <div class="dh-sum-row">
                            <span>Combo / Pack Discount</span>
                            <span class="dh-sum-discount">- &#8377;{{ number_format($totalSavings, 2) }}</span>
                        </div>
                    @endif

                    @if($couponDiscount > 0)
                        <div class="dh-sum-row">
                            <span>Coupon Discount ({{ $coupon['code'] ?? '' }})</span>
                            <span class="dh-sum-discount">- &#8377;{{ number_format($couponDiscount, 2) }}</span>
                        </div>
                    @endif

                    <div class="dh-sum-row">
                        <span>Pan-India Express Delivery</span>
                        <span class="dh-sum-free"><i class="bi bi-truck"></i> FREE</span>
                    </div>

                    <div class="dh-sum-total-row">
                        <span class="dh-sum-total-lbl">Total Payable</span>
                        <span class="dh-sum-total-val">&#8377;{{ number_format($finalTotal, 2) }}</span>
                    </div>
                    <div class="dh-sum-tax-note">Inclusive of all applicable GST &amp; taxes</div>

                    <!-- Checkout Button -->
                    <a href="{{ route('checkout.index') }}" class="dh-btn-checkout">
                        <span>PROCEED TO CHECKOUT</span>
                        <i class="bi bi-arrow-right-short" style="font-size:1.4rem;"></i>
                    </a>

                    <a href="{{ route('shop') }}" class="dh-continue-shop-link">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>

                    <!-- Reassurance & Trust List -->
                    <div class="dh-trust-list">
                        <div class="dh-trust-item">
                            <i class="bi bi-shield-lock-fill"></i>
                            <span>256-Bit Bank-Grade SSL Encrypted Checkout</span>
                        </div>
                        <div class="dh-trust-item">
                            <i class="bi bi-truck"></i>
                            <span>Free Priority Shipping with Live Shiprocket Tracking</span>
                        </div>
                        <div class="dh-trust-item">
                            <i class="bi bi-flower1"></i>
                            <span>100% Classical Organic &amp; AYUSH Certified</span>
                        </div>
                        <div class="dh-trust-item">
                            <i class="bi bi-wallet2"></i>
                            <span>Cash on Delivery (COD) &amp; Instant UPI Accepted</span>
                        </div>
                    </div>

                    <!-- Payment App Icons -->
                    <div class="dh-pay-icons-row" title="Accepted Payment Methods">
                        <i class="bi bi-credit-card-2-front" title="Cards"></i>
                        <i class="bi bi-qr-code-scan" title="UPI Apps"></i>
                        <i class="bi bi-bank" title="Netbanking"></i>
                        <i class="bi bi-cash-stack" title="Cash on Delivery"></i>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>

<script>
// Quantity Stepper AJAX Handler
function changeQty(productId, delta) {
    const card = document.getElementById('cartCard-' + productId);
    if (!card) return;
    const input = card.querySelector('.dh-stepper-val');
    let currentQty = parseInt(input.value) || 1;
    let newQty = currentQty + delta;

    if (newQty < 1) {
        removeCartItem(productId);
        return;
    }

    // Disable buttons temporarily to prevent double click
    card.querySelectorAll('.dh-stepper-btn').forEach(b => b.disabled = true);

    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId, quantity: newQty })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Could not update cart quantity.');
            card.querySelectorAll('.dh-stepper-btn').forEach(b => b.disabled = false);
        }
    })
    .catch(() => {
        window.location.reload();
    });
}

// Remove item from cart
function removeCartItem(productId, productName) {
    const confirmText = productName 
        ? 'Remove "' + productName + '" from your shopping bag?'
        : 'Remove this product from your shopping bag?';

    if (!confirm(confirmText)) {
        return;
    }

    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        window.location.reload();
    })
    .catch(() => {
        window.location.reload();
    });
}

// Apply Coupon AJAX
function applyCoupon(event) {
    event.preventDefault();
    const input = document.getElementById('couponCodeInput');
    const msg = document.getElementById('couponMsg');
    const btn = document.getElementById('couponBtn');
    const code = input ? input.value.trim() : '';

    if (!code) return;

    btn.disabled = true;
    btn.innerText = '...';

    fetch('{{ route('cart.applyCoupon') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ code: code })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            msg.style.display = 'block';
            msg.style.color = '#ef4444';
            msg.innerText = data.message || 'Invalid coupon code.';
            btn.disabled = false;
            btn.innerText = 'APPLY';
        }
    })
    .catch(() => {
        window.location.reload();
    });
}

// Remove Coupon AJAX
function removeCoupon() {
    fetch('{{ route('cart.removeCoupon') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        window.location.reload();
    })
    .catch(() => {
        window.location.reload();
    });
}
</script>

@endsection
