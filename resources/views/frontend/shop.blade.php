@extends('layouts.frontend')

@section('title', 'Our Ayurvedic Collection - Dhuria')

@section('content')
<div class="container" style="max-width: 1280px; margin: 0 auto; padding: 40px 20px 80px 20px;">
    <!-- Centered Header matching Image 2 -->
    <div style="margin-bottom: 30px; text-align: center;">
        <h1 style="font-size: 2.3rem; font-weight: 800; color: #081a30; margin: 0 0 10px 0; letter-spacing: -0.02em; text-transform: uppercase;">
            OUR AYURVEDIC COLLECTION
        </h1>
        <p style="font-size: 0.95rem; color: #64748b; margin: 0 auto 16px auto; max-width: 680px; line-height: 1.5;">
            Meticulously crafted 100% natural formulations for holistic healing and balanced living.
        </p>

        <!-- Sleek Horizontal Scrollable Category Filter Pills -->
        <div class="dh-shop-cat-bar-wrap">
            <div class="dh-shop-cat-rail">
                <a href="{{ route('shop') }}" class="dh-shop-cat-pill {{ !request('cat') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i> All Formulations
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('shop', array_merge(request()->except(['cat', 'page']), ['cat' => $cat->slug])) }}" 
                       class="dh-shop-cat-pill {{ request('cat') == $cat->slug ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Toolbar: Count + Active Filters + Sort By + Mobile Filter Drawer Button -->
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; padding: 12px 18px; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <div style="font-size: 0.85rem; font-weight: 700; color: #475569;">
            Showing <span style="color: #081a30; font-weight: 800;">{{ $products->total() }}</span> Formulations
            @if(request('search'))
                for "<span style="color: #081a30;">{{ request('search') }}</span>"
            @endif
        </div>

        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            @if(request()->hasAny(['cat', 'search', 'sort_by', 'highlight', 'max_price']))
                <a href="{{ route('shop') }}" style="font-size: 0.75rem; font-weight: 700; color: #ef4444; background: #fef2f2; border: 1px solid #fecaca; border-radius: 20px; padding: 5px 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="bi bi-x-circle-fill"></i> Clear Filters
                </a>
            @endif

            <!-- Sort By Selector -->
            <form action="{{ route('shop') }}" method="GET" style="display: flex; align-items: center; margin: 0;">
                @foreach(request()->except(['sort_by', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <label for="shopSortBy" style="font-size: 0.78rem; font-weight: 700; color: #64748b; margin-right: 6px; white-space: nowrap;">Sort by:</label>
                <select id="shopSortBy" name="sort_by" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 0.8rem; font-weight: 700; color: #081a30; background: #f8fafc; outline: none; cursor: pointer;">
                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Newest First</option>
                    <option value="bestseller" {{ request('sort_by') == 'bestseller' ? 'selected' : '' }}>Best Selling</option>
                    <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Full-Width Products Grid (4 Columns Desktop matching Image 2) -->
    @if(isset($products) && $products->count() > 0)
        <div class="dh-collection-grid">
            @foreach($products as $product)
                @php
                    $inWishlist = in_array($product->id, session()->get('wishlist', []));
                @endphp
                <div class="dh-product-card" onclick="if (!event.target.closest('.dh-card-wishlist, .dh-btn-card-add')) window.location.href='{{ route('product.show', $product->slug) }}';">
                    @if(str_contains(strtolower($product->name), 'kit'))
                        <span class="dh-card-badge">Kit</span>
                    @endif

                    <button type="button" class="dh-card-wishlist" onclick="Ebigcart.toggleWishlist('{{ $product->id }}', this, event)" style="{{ $inWishlist ? 'color:#ef4444;' : '' }}" title="Wishlist">
                        <i class="bi {{ $inWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                    </button>

                    <div class="dh-card-img-box">
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" loading="lazy">
                    </div>

                    <div class="dh-card-body">
                        <a href="{{ route('product.show', $product->slug) }}" class="dh-card-title">{{ $product->name }}</a>

                        <div class="dh-card-rating">
                            <span>★★★★★</span>
                            <span class="dh-rating-num">{{ $product->id == 17 ? '4.5' : '5.0' }}</span>
                            <span class="dh-reviews-cnt">({{ $product->id == 17 ? '7' : ($product->id == 21 ? '12' : '1') }})</span>
                        </div>

                        <div class="dh-card-price-row">
                            <span class="dh-card-price-current">₹{{ number_format($product->sale_price ?? $product->price, 0) }}</span>
                            @if($product->sale_price)
                                <span class="dh-card-price-old">₹{{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>

                        <button type="button" class="dh-btn-card-add" onclick="Ebigcart.addToCart('{{ $product->id }}', 1, event)">
                            <i class="bi bi-bag"></i> ADD TO BAG
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if($products->hasPages())
            <div style="margin-top: 50px; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; max-width: 500px; margin: 0 auto;">
            <i class="bi bi-basket" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #081a30; margin-bottom: 6px;">No products found</h3>
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 20px;">We couldn't find any formulations matching your selected category.</p>
            <a href="{{ route('shop') }}" style="display: inline-block; background: #081a30; color: #ffffff; padding: 10px 24px; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; text-decoration: none;">View All Formulations</a>
        </div>
    @endif
</div>

<style>
/* SLEEK HORIZONTAL SWIPEABLE CATEGORY CHIPS BAR */
.dh-shop-cat-bar-wrap {
    margin-top: 18px;
    margin-bottom: 6px;
    width: 100%;
}
.dh-shop-cat-rail {
    display: flex;
    align-items: center;
    gap: 8px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 6px 4px 10px 4px;
    justify-content: center;
}
.dh-shop-cat-rail::-webkit-scrollbar {
    display: none;
}
.dh-shop-cat-pill {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    background: #ffffff;
    color: #475569;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    transition: all 0.2s ease;
    white-space: nowrap;
}
.dh-shop-cat-pill:hover {
    border-color: #cbd5e1;
    color: #081a30;
    background: #f8fafc;
}
.dh-shop-cat-pill.active {
    background: #081a30;
    color: #ffffff;
    border-color: #081a30;
    box-shadow: 0 4px 12px rgba(8, 26, 48, 0.2);
}
.dh-shop-cat-pill.active i {
    color: #d4af37;
}

@media (max-width: 820px) {
    .dh-shop-cat-rail {
        justify-content: flex-start;
        padding-left: 2px;
        padding-right: 2px;
    }
    .dh-shop-cat-pill {
        padding: 7px 14px;
        font-size: 12px;
    }
}

/* 4-COLUMN RESPONSIVE COLLECTION GRID MATCHING IMAGE 2 */
.dh-collection-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

@media (max-width: 1100px) {
    .dh-collection-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .dh-collection-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px !important;
    }
    .dh-card-img-box {
        height: 170px !important;
    }
    .dh-card-body {
        padding: 12px 10px 14px 10px !important;
    }
    .dh-card-title {
        font-size: 13px !important;
        height: 36px !important;
        margin-bottom: 4px !important;
    }
    .dh-card-price-current {
        font-size: 15px !important;
    }
    .dh-card-price-old {
        font-size: 11px !important;
    }
    .dh-btn-card-add {
        padding: 9px 8px !important;
        font-size: 10px !important;
    }
}

@media (max-width: 420px) {
    .dh-collection-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
    }
    .dh-product-card {
        border-radius: 14px !important;
    }
    .dh-card-img-box {
        height: 145px !important;
        border-radius: 14px 14px 0 0 !important;
    }
    .dh-card-title {
        font-size: 11.5px !important;
        line-height: 1.3 !important;
        height: 30px !important;
    }
    .dh-card-price-current {
        font-size: 14px !important;
    }
    .dh-card-price-old {
        font-size: 10.5px !important;
    }
    .dh-btn-card-add {
        padding: 8px 6px !important;
        font-size: 9.5px !important;
        letter-spacing: 0.02em !important;
    }
}

/* PRODUCT CARD DESIGN */
.dh-product-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
    padding: 0;
    display: flex;
    flex-direction: column;
    position: relative;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    height: 100%;
    overflow: hidden;
    cursor: pointer;
}
.dh-product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(8, 26, 48, 0.1);
    border-color: #cbd5e1;
}
.dh-card-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #0f172a;
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 6px;
    text-transform: capitalize;
    z-index: 10;
}
.dh-card-wishlist {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    cursor: pointer;
    color: #64748b;
    z-index: 10;
    transition: transform 0.2s, color 0.2s;
}
.dh-card-wishlist:hover {
    transform: scale(1.1);
    color: #ef4444;
}
.dh-card-img-box {
    width: 100%;
    height: 220px;
    overflow: hidden;
    position: relative;
    background: #f8fafc;
    border-radius: 20px 20px 0 0;
    padding: 0;
    margin: 0;
}
.dh-card-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    transition: transform 0.35s ease;
}
.dh-product-card:hover .dh-card-img-box img {
    transform: scale(1.05);
}
.dh-card-body {
    padding: 16px 16px 20px 16px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    flex-grow: 1;
}
.dh-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
    line-height: 1.35;
    text-decoration: none !important;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 38px;
}
.dh-card-title:hover {
    color: #d4953a;
}
.dh-card-rating {
    font-size: 12px;
    color: #f59e0b;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}
.dh-card-rating .dh-rating-num {
    font-weight: 800;
    color: #0f172a;
    font-size: 11px;
    margin-left: 2px;
}
.dh-card-rating .dh-reviews-cnt {
    color: #94a3b8;
    font-size: 11px;
}
.dh-card-price-row {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 8px;
    margin-bottom: 12px;
}
.dh-card-price-current {
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
}
.dh-card-price-old {
    font-size: 13px;
    color: #94a3b8;
    text-decoration: line-through;
}
.dh-btn-card-add {
    width: 100%;
    background: #d4953a;
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 11px 16px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: background 0.2s ease;
    margin-top: auto;
}
.dh-btn-card-add:hover {
    background: #c27d1a;
    color: #ffffff;
}
</style>
@endsection
