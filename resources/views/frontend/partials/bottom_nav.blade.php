<!-- ========================================================
     DHURIA WEBAPP MOBILE BOTTOM NAVIGATION BAR
     ======================================================== -->
<nav class="dh-bottom-app-nav" aria-label="Mobile Navigation">
    <a href="{{ route('home') }}" class="dh-bottom-nav-item {{ request()->is('/') ? 'active' : '' }}">
        <i class="bi {{ request()->is('/') ? 'bi-house-fill' : 'bi-house' }}"></i>
        <span>Home</span>
    </a>
    
    <a href="{{ route('shop') }}" class="dh-bottom-nav-item {{ request()->is('shop*') ? 'active' : '' }}">
        <i class="bi {{ request()->is('shop*') ? 'bi-grid-fill' : 'bi-grid' }}"></i>
        <span>Shop</span>
    </a>
    
    <button type="button" class="dh-bottom-nav-item" onclick="triggerSearchModal(event)">
        <i class="bi bi-search"></i>
        <span>Search</span>
    </button>
    
    <a href="{{ route('wishlist.index') }}" class="dh-bottom-nav-item {{ request()->is('wishlist*') ? 'active' : '' }}">
        <div class="dh-bottom-nav-icon-wrap">
            <i class="bi {{ request()->is('wishlist*') ? 'bi-heart-fill' : 'bi-heart' }}"></i>
            <span class="dh-bottom-nav-badge wishlist-count-badge">0</span>
        </div>
        <span>Wishlist</span>
    </a>
    
    <a href="{{ route('cart.index') }}" class="dh-bottom-nav-item {{ request()->is('cart*') ? 'active' : '' }}">
        <div class="dh-bottom-nav-icon-wrap">
            <i class="bi {{ request()->is('cart*') ? 'bi-bag-fill' : 'bi-bag' }}"></i>
            <span class="dh-bottom-nav-badge cart-count-badge">0</span>
        </div>
        <span>Bag</span>
    </a>
</nav>

<style>
.dh-bottom-app-nav {
    display: none;
}
@media (max-width: 992px) {
    .dh-bottom-app-nav {
        display: flex !important;
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        height: 60px !important;
        background: #081a30 !important;
        border-top: 1px solid rgba(212, 175, 55, 0.3) !important;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.45) !important;
        z-index: 9998 !important;
        align-items: center !important;
        justify-content: space-around !important;
        padding: 0 4px !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
    }
    .dh-bottom-nav-item {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        color: #94a3b8 !important;
        text-decoration: none !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        gap: 3px !important;
        background: none !important;
        border: none !important;
        cursor: pointer !important;
        padding: 6px 10px !important;
        transition: all 0.2s ease !important;
        min-width: 56px !important;
        -webkit-tap-highlight-color: transparent !important;
    }
    .dh-bottom-nav-item i {
        font-size: 1.25rem !important;
        line-height: 1 !important;
        transition: transform 0.2s ease, color 0.2s ease !important;
    }
    .dh-bottom-nav-item:hover, 
    .dh-bottom-nav-item.active {
        color: #d4af37 !important;
    }
    .dh-bottom-nav-item.active i {
        transform: translateY(-2px) !important;
        color: #d4af37 !important;
    }
    .dh-bottom-nav-icon-wrap {
        position: relative !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .dh-bottom-nav-badge {
        position: absolute !important;
        top: -6px !important;
        right: -10px !important;
        background: #ef4444 !important;
        color: #ffffff !important;
        font-size: 9px !important;
        font-weight: 800 !important;
        border-radius: 10px !important;
        padding: 1px 5px !important;
        min-width: 16px !important;
        text-align: center !important;
        line-height: 1.2 !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3) !important;
    }
    body {
        padding-bottom: 65px !important;
    }
    .dhuria-float-whatsapp {
        bottom: 78px !important;
    }
}
</style>

<script>
function triggerSearchModal(e) {
    if (e) e.preventDefault();
    const modal = document.getElementById('floraSearchModalBackdrop');
    if (modal) {
        modal.classList.add('active');
        const input = document.getElementById('floraSearchInput');
        if (input) setTimeout(() => input.focus(), 150);
    }
}
</script>
