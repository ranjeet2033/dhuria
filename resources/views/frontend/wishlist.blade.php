@extends('layouts.frontend')

@section('title', 'Your Wishlist - Dhuria Ayurveda')

@section('content')
<style>
    /* Product card style identical to Shop & Home */
    .dh-wish-card {
        background: #ffffff;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }
    .dh-wish-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(8, 26, 48, 0.08);
    }
    .dh-wish-img-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 1/1;
        background: radial-gradient(circle, #f8fafc 0%, #eef2f6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        overflow: hidden;
    }
    .dh-wish-img-wrap img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        transition: transform 0.35s ease;
    }
    .dh-wish-card:hover .dh-wish-img-wrap img {
        transform: scale(1.06);
    }
    .dh-wish-heart-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        border: none;
        cursor: pointer;
        transition: transform 0.2s, background 0.2s;
        z-index: 5;
    }
    .dh-wish-heart-btn:hover {
        transform: scale(1.12);
        background: #ffffff;
    }
    .dh-wish-body {
        padding: 14px 16px 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
        justify-content: space-between;
        text-align: center;
    }
    .dh-wish-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        line-height: 1.35;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .dh-wish-title:hover {
        color: #c8923a;
    }
    .dh-wish-stars {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        color: #f59e0b;
        font-size: 0.72rem;
        margin-bottom: 8px;
    }
    .dh-wish-prices {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 6px;
        margin-bottom: 12px;
    }
    .dh-wish-price-current {
        font-size: 1rem;
        font-weight: 800;
        color: #081a30;
    }
    .dh-wish-price-orig {
        font-size: 0.8rem;
        color: #94a3b8;
        text-decoration: line-through;
    }
    .dh-wish-btn-add {
        background: #c8923a;
        color: #ffffff;
        border: none;
        border-radius: 9px;
        padding: 9px 12px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        width: 100%;
        cursor: pointer;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 6px rgba(200, 146, 58, 0.25);
    }
    .dh-wish-btn-add:hover {
        background: #b07d2c;
        box-shadow: 0 4px 10px rgba(200, 146, 58, 0.35);
    }
</style>

<div class="relative min-h-[75vh] bg-[#fbfcfd] py-10 px-4 sm:px-6 lg:px-8 overflow-hidden" style="font-family: 'Outfit', sans-serif;">
    <!-- Botanical Leaf SVG Decoration Top Left -->
    <svg class="absolute top-4 -left-6 w-36 h-36 opacity-30 pointer-events-none text-emerald-800" viewBox="0 0 120 120" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M10 110 Q 50 80, 80 40 Q 95 20, 110 10" stroke-linecap="round"/>
        <path d="M40 90 Q 60 70, 50 50 Q 30 70, 40 90 Z" fill="currentColor" fill-opacity="0.15"/>
        <path d="M65 65 Q 85 45, 95 30 Q 80 45, 65 65 Z" fill="currentColor" fill-opacity="0.2"/>
        <path d="M25 100 Q 15 80, 25 70 Q 35 85, 25 100 Z" fill="currentColor" fill-opacity="0.12"/>
    </svg>

    <!-- Botanical Leaf SVG Decoration Mid Right -->
    <svg class="absolute top-1/3 -right-4 w-28 h-28 opacity-25 pointer-events-none text-emerald-800" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M90 10 Q 60 40, 30 70 Q 15 85, 10 90" stroke-linecap="round"/>
        <path d="M55 45 Q 75 30, 85 40 Q 65 60, 55 45 Z" fill="currentColor" fill-opacity="0.15"/>
        <path d="M35 65 Q 45 50, 60 55 Q 45 75, 35 65 Z" fill="currentColor" fill-opacity="0.2"/>
    </svg>

    <div class="max-w-7xl mx-auto relative z-10">
        <!-- Page Heading Matching Reference -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Outfit', sans-serif;">
                Your Wishlist
            </h1>
        </div>

        @if(isset($products) && $products->count() > 0)
            <!-- Populated Wishlist Grid -->
            <div id="wishlist-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $product)
                    <div id="wishlist-item-{{ $product->id }}" class="dh-wish-card">
                        <!-- Image Wrap with Remove from Wishlist Heart Button -->
                        <div class="dh-wish-img-wrap">
                            <button type="button" 
                                    class="dh-wish-heart-btn" 
                                    onclick="removeWishlistItem('{{ $product->id }}', this, event)" 
                                    title="Remove from Wishlist">
                                <i class="fa-solid fa-heart text-sm"></i>
                            </button>
                            <a href="{{ route('product.show', $product->slug) }}" class="w-full h-full flex items-center justify-center">
                                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" loading="lazy">
                            </a>
                        </div>

                        <!-- Card Body -->
                        <div class="dh-wish-body">
                            <div>
                                <a href="{{ route('product.show', $product->slug) }}" class="dh-wish-title">
                                    {{ $product->name }}
                                </a>

                                <div class="dh-wish-stars">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                    <span class="text-[11px] font-bold text-slate-600 ml-1">4.8</span>
                                </div>
                            </div>

                            <div>
                                <div class="dh-wish-prices">
                                    @if($product->sale_price)
                                        <span class="dh-wish-price-current">&#8377;{{ number_format($product->sale_price, 0) }}</span>
                                        <span class="dh-wish-price-orig">&#8377;{{ number_format($product->price, 0) }}</span>
                                    @else
                                        <span class="dh-wish-price-current">&#8377;{{ number_format($product->price, 0) }}</span>
                                    @endif
                                </div>

                                <button type="button" 
                                        class="dh-wish-btn-add" 
                                        onclick="Ebigcart.addToCart('{{ $product->id }}', 1, event)">
                                    ADD TO BAG
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State Placeholder (hidden until last item removed) -->
            <div id="wishlist-empty-fallback" class="hidden max-w-2xl mx-auto bg-white border border-slate-200/80 rounded-3xl p-12 sm:p-20 text-center shadow-xs my-8">
                <div class="w-16 h-16 rounded-full border-2 border-slate-300 flex items-center justify-center mx-auto mb-5 text-slate-800">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-6">Your wishlist is empty.</h3>
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-[#081a30] hover:bg-[#0d2544] text-white text-xs font-bold uppercase tracking-widest py-3.5 px-8 rounded-full transition shadow-md">
                    EXPLORE COLLECTION &rarr;
                </a>
            </div>

        @else
            <!-- Empty State Matching Reference Image 3 Exactly -->
            <div class="max-w-2xl mx-auto bg-white border border-slate-200/80 rounded-3xl p-12 sm:p-20 text-center shadow-xs my-8">
                <div class="w-16 h-16 rounded-full border-2 border-slate-300 flex items-center justify-center mx-auto mb-5 text-slate-800">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-6">Your wishlist is empty.</h3>
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-[#081a30] hover:bg-[#0d2544] text-white text-xs font-bold uppercase tracking-widest py-3.5 px-8 rounded-full transition shadow-md">
                    EXPLORE COLLECTION &rarr;
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function removeWishlistItem(productId, btnEl, event) {
    if (window.Ebigcart && typeof window.Ebigcart.toggleWishlist === 'function') {
        window.Ebigcart.toggleWishlist(productId, btnEl, event);
    } else {
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        });
    }

    const item = document.getElementById('wishlist-item-' + productId);
    if (item) {
        item.style.transition = 'all 0.3s ease';
        item.style.opacity = '0';
        item.style.transform = 'scale(0.9)';
        setTimeout(() => {
            item.remove();
            const grid = document.getElementById('wishlist-grid');
            if (grid && grid.querySelectorAll('.dh-wish-card').length === 0) {
                grid.classList.add('hidden');
                const emptyFallback = document.getElementById('wishlist-empty-fallback');
                if (emptyFallback) {
                    emptyFallback.classList.remove('hidden');
                }
            }
        }, 300);
    }
}
</script>
@endsection
