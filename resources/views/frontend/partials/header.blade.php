@php
    $headerCategories = \App\Models\Category::parents()->with('children.children')->where('is_active', true)->get();
    $searchFeaturedProducts = \App\Models\Product::where('is_active', true)->take(4)->get();
@endphp

<!-- ========================================================
     FLORAMADE LUXURY HEADER
     ======================================================== -->
<header class="flora-header">
  <style>
    :root {
      --fl-navy-dark: #061528;
      --fl-navy-main: #081a30;
      --fl-navy-light: #0d2544;
      --fl-gold: #d4af37;
      --fl-gold-bright: #f59e0b;
      --fl-gold-light: #fef08a;
    }

    html, body, #page, .site, header, .flora-header {
      margin-top: 0 !important;
      padding-top: 0 !important;
      top: 0 !important;
    }

    .flora-header {
      background: var(--fl-navy-dark);
      color: #ffffff;
      font-family: 'Cinzel', 'Outfit', sans-serif;
      position: sticky;
      top: 0;
      z-index: 9999;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
      border-bottom: 1px solid rgba(212, 175, 55, 0.25);
    }

    /* 1. TOP UTILITY / ANNOUNCEMENT BAR */
    .flora-top-bar {
      background: #030c18;
      color: #cbd5e1;
      padding: 6px 30px;
      font-size: 0.76rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1550px;
      margin: 0 auto;
      letter-spacing: 0.3px;
    }
    .flora-top-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .flora-social-link {
      color: #94a3b8;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.06);
      font-size: 0.72rem;
      transition: all 0.2s ease;
    }
    .flora-social-link:hover {
      background: var(--fl-gold);
      color: var(--fl-navy-dark);
      transform: translateY(-1px);
    }
    .flora-top-left-text {
      color: #cbd5e1;
      font-weight: 500;
      margin-left: 4px;
    }

    .flora-top-center {
      color: #fef08a;
      font-weight: 600;
      letter-spacing: 0.5px;
      text-align: center;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .flora-top-right {
      display: flex;
      align-items: center;
      gap: 14px;
      color: #cbd5e1;
    }
    .flora-top-contact-link {
      color: #cbd5e1;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: color 0.2s;
    }
    .flora-top-contact-link:hover {
      color: var(--fl-gold);
    }

    /* 2. MAIN HEADER NAVIGATION BAR */
    .flora-main-nav {
      max-width: 1550px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 30px;
      position: relative;
    }

    /* Brand Logo */
    .flora-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none !important;
    }
    .flora-brand-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 1.5px solid var(--fl-gold);
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle, #254972 0%, #0c233f 100%);
      color: var(--fl-gold);
      font-size: 1.15rem;
      box-shadow: 0 0 10px rgba(212, 175, 55, 0.4);
    }
    .flora-brand-text {
      font-family: 'Cinzel', serif;
      font-size: 1.75rem;
      font-weight: 800;
      letter-spacing: 1.5px;
      background: linear-gradient(135deg, #ffffff 30%, #fef08a 70%, #d4af37 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin: 0;
      line-height: 1;
    }

    /* Center Nav Links */
    .flora-nav-links {
      display: flex;
      align-items: center;
      gap: 32px;
      list-style: none;
      margin: 0;
      padding: 0;
    }
    .flora-nav-item {
      position: relative;
    }
    .flora-nav-link {
      color: #f1f5f9;
      text-decoration: none !important;
      font-size: 0.92rem;
      font-weight: 600;
      letter-spacing: 0.3px;
      padding: 8px 0;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: color 0.25s ease;
    }
    .flora-nav-link i.arrow {
      font-size: 0.7rem;
      transition: transform 0.2s;
    }
    .flora-nav-link:hover, .flora-nav-link.active {
      color: var(--fl-gold);
    }
    .flora-nav-item:hover .flora-nav-link i.arrow {
      transform: rotate(180deg);
    }

    /* Dropdown Menus */
    .flora-dropdown {
      position: absolute;
      top: 100%;
      left: 50%;
      transform: translateX(-50%) translateY(10px);
      background: var(--fl-navy-main);
      border: 1px solid rgba(212, 175, 55, 0.35);
      border-radius: 12px;
      padding: 10px 0;
      min-width: 220px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
      opacity: 0;
      visibility: hidden;
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
      z-index: 1000;
      list-style: none;
      pointer-events: none;
    }
    .flora-nav-item:hover > .flora-dropdown {
      opacity: 1;
      visibility: visible;
      transform: translateX(-50%) translateY(0);
      pointer-events: auto;
    }
    .flora-dropdown-item {
      padding: 0;
      margin: 0;
    }
    .flora-dropdown-link {
      display: block;
      padding: 8px 20px;
      color: #cbd5e1;
      text-decoration: none !important;
      font-size: 0.85rem;
      font-weight: 500;
      transition: all 0.2s;
    }
    .flora-dropdown-link:hover {
      background: rgba(212, 175, 55, 0.12);
      color: var(--fl-gold);
      padding-left: 24px;
    }

    /* Right Action Icons */
    .flora-actions {
      display: flex;
      align-items: center;
      gap: 22px;
    }
    .flora-action-btn {
      color: #f1f5f9;
      text-decoration: none !important;
      font-size: 1.25rem;
      position: relative;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
      background: none;
      border: none;
      cursor: pointer;
      padding: 0;
    }
    .flora-action-btn:hover {
      color: var(--fl-gold);
      transform: translateY(-2px);
    }
    .flora-count-badge {
      position: absolute;
      top: -7px;
      right: -10px;
      background: var(--fl-gold-bright);
      color: #061528;
      font-size: 0.65rem;
      font-weight: 900;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1.5px solid var(--fl-navy-dark);
    }

    /* Centered Search Popup Modal (Matching Reference) */
    .flora-search-modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(3, 12, 24, 0.72);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      z-index: 100000;
      align-items: flex-start;
      justify-content: center;
      padding: 50px 16px 20px;
      overflow-y: auto;
    }
    .flora-search-modal-backdrop.active {
      display: flex;
    }
    .flora-search-modal-box {
      width: 100%;
      max-width: 580px;
      background: #ffffff;
      border-radius: 24px;
      box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4);
      padding: 24px;
      color: #0f172a;
      font-family: 'Outfit', sans-serif;
      animation: searchModalSlideDown 0.22s cubic-bezier(0.16, 1, 0.3, 1);
      border: 1px solid rgba(226, 232, 240, 0.95);
      position: relative;
    }
    @keyframes searchModalSlideDown {
      from {
        opacity: 0;
        transform: translateY(-20px) scale(0.96);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
    .flora-search-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
      background: #f8fafc;
      border: 1.5px solid #e2e8f0;
      border-radius: 16px;
      padding: 10px 14px;
      margin-bottom: 20px;
      transition: all 0.2s;
    }
    .flora-search-input-wrap:focus-within {
      background: #ffffff;
      border-color: #081a30;
      box-shadow: 0 0 0 3px rgba(8, 26, 48, 0.08);
    }
    .flora-search-input-field {
      width: 100%;
      background: transparent;
      border: none;
      outline: none;
      font-size: 0.92rem;
      font-weight: 500;
      color: #0f172a;
      padding: 0 10px;
    }
    .flora-search-input-field::placeholder {
      color: #94a3b8;
    }
    .flora-search-close-btn {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #f1f5f9;
      border: none;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      cursor: pointer;
      transition: all 0.2s;
      flex-shrink: 0;
    }
    .flora-search-close-btn:hover {
      background: #e2e8f0;
      color: #0f172a;
    }
    .flora-search-sec-title {
      font-size: 0.68rem;
      font-weight: 800;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 10px;
    }
    .flora-search-pill-list {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 20px;
    }
    .flora-search-pill {
      font-size: 0.75rem;
      font-weight: 600;
      color: #334155;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      padding: 5px 14px;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.18s;
    }
    .flora-search-pill:hover {
      background: #f8fafc;
      border-color: #081a30;
      color: #081a30;
      transform: translateY(-1px);
    }
    .flora-search-remedies-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 10px;
    }
    .flora-search-remedy-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 9px 12px;
      border-radius: 14px;
      border: 1px solid #f1f5f9;
      background: #fcfdfe;
      text-decoration: none;
      color: inherit;
      margin-bottom: 8px;
      transition: all 0.18s;
    }
    .flora-search-remedy-item:hover {
      background: #ffffff;
      border-color: #cbd5e1;
      box-shadow: 0 4px 12px rgba(8, 26, 48, 0.04);
      transform: translateX(2px);
    }
    .flora-search-remedy-thumb {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: #f8fafc;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 4px;
      flex-shrink: 0;
      overflow: hidden;
      border: 1px solid #f1f5f9;
    }
    .flora-search-remedy-thumb img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
    .flora-search-remedy-info {
      flex: 1;
      min-width: 0;
    }
    .flora-search-remedy-name {
      font-size: 0.82rem;
      font-weight: 700;
      color: #0f172a;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 3px;
    }
    .flora-search-remedy-price-row {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.78rem;
    }
    .flora-search-badge-ayur {
      font-size: 0.62rem;
      font-weight: 800;
      background: #e0f2fe;
      color: #0369a1;
      padding: 2px 7px;
      border-radius: 6px;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }
    .flora-search-quick-links {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 6px;
    }
    .flora-search-quick-link {
      font-size: 0.74rem;
      font-weight: 700;
      color: #475569;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      padding: 5px 14px;
      text-decoration: none;
      transition: all 0.18s;
    }
    .flora-search-quick-link:hover {
      background: #081a30;
      color: #ffffff;
      border-color: #081a30;
    }

    /* Mobile Hamburger Menu */
    .flora-mobile-toggle {
      display: none;
      background: none;
      border: 1px solid var(--fl-gold);
      color: var(--fl-gold);
      border-radius: 6px;
      padding: 6px 10px;
      font-size: 1.25rem;
      cursor: pointer;
    }

    .flora-mobile-drawer {
      display: none;
      background: var(--fl-navy-main);
      border-top: 1px solid rgba(212, 175, 55, 0.25);
      padding: 20px;
    }
    .flora-mobile-drawer.active {
      display: block;
    }
    .flora-mobile-drawer ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .flora-mobile-drawer li {
      margin-bottom: 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
      padding-bottom: 8px;
    }
    .flora-mobile-drawer a {
      color: #f1f5f9;
      text-decoration: none;
      font-size: 0.95rem;
      font-weight: 600;
      display: block;
    }

    .flora-mobile-search-btn {
      display: none;
    }

    @media (max-width: 992px) {
      .flora-top-left, .flora-top-right { display: none; }
      .flora-top-center { width: 100%; justify-content: center; font-size: 0.72rem; }
      .flora-nav-links { display: none; }
      .flora-main-nav {
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .flora-brand {
        margin: 0 auto;
      }
      .flora-brand img {
        height: 32px !important;
      }
      .flora-mobile-search-btn {
        display: flex !important;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: #ffffff;
        font-size: 1.15rem;
        cursor: pointer;
        padding: 6px;
        line-height: 1;
        transition: color 0.2s;
      }
      .flora-mobile-search-btn:hover {
        color: var(--fl-gold);
      }
      .flora-mobile-toggle { 
        display: flex !important; 
        align-items: center;
        justify-content: center;
        background: none;
        border: 1px solid rgba(212, 175, 55, 0.4);
        color: var(--fl-gold);
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 1.2rem;
      }
      .flora-actions {
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .flora-actions .flora-action-btn[id="floraSearchToggle"],
      .flora-actions .flora-action-btn[title="My Wishlist"],
      .flora-actions .flora-action-btn[title="My Account"],
      .flora-actions .flora-action-btn[title="Login / Register"] {
        display: none !important;
      }
    }
  </style>

  <!-- 1. Top Utility Notification Bar -->
  <div class="flora-top-bar">
    <div class="flora-top-left">
      <a href="https://facebook.com" target="_blank" class="flora-social-link" title="Facebook"><i class="bi bi-facebook"></i></a>
      <a href="https://instagram.com" target="_blank" class="flora-social-link" title="Instagram"><i class="bi bi-instagram"></i></a>
      <span class="flora-top-left-text">Follow us!</span>
    </div>

    <div class="flora-top-center">
      <span>🎉</span> Festive Offer: Save Up to 20% + Extra Combo OFF!
    </div>

    <div class="flora-top-right">
      <span>Get in touch with us</span>
      <a href="tel:{{ \App\Models\Setting::get('site_phone', '+91 9259449933') }}" class="flora-top-contact-link" title="Call Us">
        <i class="bi bi-telephone-fill" style="color:var(--fl-gold);"></i>
      </a>
      <a href="mailto:{{ \App\Models\Setting::get('site_email', 'contact@dhuria.com') }}" class="flora-top-contact-link" title="Email Us">
        <i class="bi bi-envelope-fill" style="color:var(--fl-gold);"></i>
      </a>
    </div>
  </div>

  <!-- 2. Main Navigation Bar -->
  <div class="flora-main-nav">
    <!-- Left: Mobile Search Button (Matching Mobile Reference Header) -->
    <button type="button" class="flora-mobile-search-btn" onclick="triggerSearchModal(event)" title="Search Remedies">
      <i class="bi bi-search"></i>
    </button>

    <!-- Brand Logo -->
    <a href="/" class="flora-brand">
      <img src="{{ asset('images/dhuria_logo_white.png') }}" alt="dhuria" style="height: 38px; width: auto; object-fit: contain;">
    </a>

    <!-- Center Nav Links -->
    <ul class="flora-nav-links">
      <li class="flora-nav-item">
        <a href="/" class="flora-nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
      </li>

      <!-- MadhuCurex / Shop with Dropdown -->
      <li class="flora-nav-item">
        <a href="{{ route('shop') }}" class="flora-nav-link {{ request()->is('shop') ? 'active' : '' }}">
          <span>MadhuCurex</span>
          <i class="bi bi-chevron-down arrow"></i>
        </a>
        <ul class="flora-dropdown">
          <li class="flora-dropdown-item">
            <a href="{{ route('shop') }}" class="flora-dropdown-link">All Products</a>
          </li>
          @foreach($headerCategories as $hCat)
            <li class="flora-dropdown-item">
              <a href="{{ route('shop', ['cat' => $hCat->slug]) }}" class="flora-dropdown-link">{{ $hCat->name }}</a>
            </li>
          @endforeach
        </ul>
      </li>

      <li class="flora-nav-item">
        <a href="{{ route('about') }}" class="flora-nav-link {{ request()->is('about') ? 'active' : '' }}">About Us</a>
      </li>

      <li class="flora-nav-item">
        <a href="{{ route('contact') }}" class="flora-nav-link {{ request()->is('contact') ? 'active' : '' }}">Contact Us</a>
      </li>

      <!-- Policy Dropdown -->
      <li class="flora-nav-item">
        <a href="javascript:void(0)" class="flora-nav-link">
          <span>Policy</span>
          <i class="bi bi-chevron-down arrow"></i>
        </a>
        <ul class="flora-dropdown">
          <li class="flora-dropdown-item"><a href="{{ route('terms') }}" class="flora-dropdown-link">Terms &amp; Conditions</a></li>
          <li class="flora-dropdown-item"><a href="{{ route('privacy') }}" class="flora-dropdown-link">Privacy Policy</a></li>
          <li class="flora-dropdown-item"><a href="{{ route('refund') }}" class="flora-dropdown-link">Refund &amp; Return Policy</a></li>
          <li class="flora-dropdown-item"><a href="{{ route('shipping') }}" class="flora-dropdown-link">Shipping Policy</a></li>
          <li class="flora-dropdown-item"><a href="{{ route('cancellation') }}" class="flora-dropdown-link">Cancellation Policy</a></li>
        </ul>
      </li>
    </ul>

    <!-- Right Action Icons -->
    <div class="flora-actions">
      <!-- Profile / User Account -->
      @auth
        <a href="{{ route('dashboard') }}" class="flora-action-btn" title="My Account">
          <i class="bi bi-person"></i>
        </a>
      @else
        <a href="{{ route('login') }}" class="flora-action-btn" title="Login / Register">
          <i class="bi bi-person"></i>
        </a>
      @endauth

      <!-- Search Trigger Icon -->
      <button type="button" class="flora-action-btn" id="floraSearchToggle" title="Search Products">
        <i class="bi bi-search"></i>
      </button>

      <!-- Wishlist Heart Icon -->
      <a href="{{ route('wishlist.index') }}" class="flora-action-btn" title="My Wishlist">
        <i class="bi bi-heart"></i>
        <span class="flora-count-badge wishlist-count-badge">0</span>
      </a>

      <!-- Shopping Bag Icon -->
      <a href="{{ route('cart.index') }}" class="flora-action-btn" title="Shopping Cart">
        <i class="bi bi-bag"></i>
        <span class="flora-count-badge cart-count-badge">0</span>
      </a>

      <!-- Mobile Hamburger Toggle (Right side on mobile) -->
      <button class="flora-mobile-toggle" id="floraMobileToggle" aria-label="Toggle Menu" style="display:none;">
        <i class="bi bi-list"></i>
      </button>
    </div>

    <!-- Centered Search Popup Modal (Matching Reference Image 4) -->
    <div class="flora-search-modal-backdrop" id="floraSearchModalBackdrop">
      <div class="flora-search-modal-box" id="floraSearchModalBox" onclick="event.stopPropagation()">
        <!-- Search Input Form -->
        <form action="{{ route('shop') }}" method="GET" id="floraSearchForm">
          <div class="flora-search-input-wrap">
            <i class="bi bi-search" style="color: #94a3b8; font-size: 1.05rem; flex-shrink: 0;"></i>
            <input type="text" name="search" id="floraSearchInput" class="flora-search-input-field" placeholder="Search remedies, kits..." value="{{ request('search') }}" autocomplete="off">
            <button type="button" class="flora-search-close-btn" id="floraSearchCloseBtn" title="Close Search">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
        </form>

        <!-- Popular Searches Section -->
        <div class="flora-search-sec-title">
          <i class="bi bi-stars" style="color: #f59e0b;"></i>
          <span>Popular Searches</span>
        </div>
        <div class="flora-search-pill-list">
          <a href="{{ route('shop', ['search' => 'Pilecurex Kit']) }}" class="flora-search-pill">Pilecurex Kit</a>
          <a href="{{ route('shop', ['search' => 'MadhuCurex']) }}" class="flora-search-pill">MadhuCurex</a>
          <a href="{{ route('shop', ['search' => 'Liver Detox']) }}" class="flora-search-pill">Liver Detox</a>
          <a href="{{ route('shop', ['search' => 'Paachan Sathi']) }}" class="flora-search-pill">Paachan Sathi</a>
          <a href="{{ route('shop', ['search' => 'Diabetes Care']) }}" class="flora-search-pill">Diabetes Care</a>
        </div>

        <!-- Featured Remedies Section -->
        <div class="flora-search-remedies-header">
          <div class="flora-search-sec-title" style="margin-bottom: 0;">
            <i class="bi bi-activity" style="color: #0284c7;"></i>
            <span>Featured Remedies</span>
          </div>
          <a href="{{ route('shop') }}" style="font-size: 0.72rem; font-weight: 700; color: #475569; text-decoration: none;">View all &rarr;</a>
        </div>

        <div id="floraSearchRemediesList">
          @foreach($searchFeaturedProducts as $sProd)
            <a href="{{ route('product.show', $sProd->slug) }}" class="flora-search-remedy-item" data-title="{{ strtolower($sProd->name) }}">
              <div class="flora-search-remedy-thumb">
                <img src="{{ $sProd->primary_image_url }}" alt="{{ $sProd->name }}">
              </div>
              <div class="flora-search-remedy-info">
                <div class="flora-search-remedy-name">{{ $sProd->name }}</div>
                <div class="flora-search-remedy-price-row">
                  @if($sProd->sale_price)
                    <strong style="color: #081a30; font-weight: 800;">&#8377;{{ number_format($sProd->sale_price, 0) }}</strong>
                    <span style="color: #94a3b8; text-decoration: line-through; font-size: 0.72rem;">&#8377;{{ number_format($sProd->price, 0) }}</span>
                  @else
                    <strong style="color: #081a30; font-weight: 800;">&#8377;{{ number_format($sProd->price, 0) }}</strong>
                  @endif
                  <span class="flora-search-badge-ayur">Ayurvedic</span>
                </div>
              </div>
              <i class="bi bi-chevron-right" style="color: #cbd5e1; font-size: 0.85rem; margin-left: auto;"></i>
            </a>
          @endforeach
        </div>

        <!-- Quick Links Section -->
        <div class="flora-search-sec-title" style="margin-top: 18px; margin-bottom: 8px;">
          <span>Quick Links</span>
        </div>
        <div class="flora-search-quick-links">
          <a href="{{ route('shop') }}" class="flora-search-quick-link">All Products</a>
          <a href="{{ route('about') }}" class="flora-search-quick-link">About Us</a>
          <a href="{{ route('contact') }}" class="flora-search-quick-link">Contact Support</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Drawer Menu -->
  <div class="flora-mobile-drawer" id="floraMobileDrawer">
    <ul>
      <li><a href="/">Home</a></li>
      <li><a href="{{ route('shop') }}">MadhuCurex / Shop All</a></li>
      @foreach($headerCategories as $hCat)
        <li style="padding-left: 15px;"><a href="{{ route('shop', ['cat' => $hCat->slug]) }}">&bull; {{ $hCat->name }}</a></li>
      @endforeach
      <li><a href="{{ route('about') }}">About Us</a></li>
      <li><a href="{{ route('contact') }}">Contact Us</a></li>
      <li><a href="{{ route('terms') }}">Terms &amp; Conditions</a></li>
      <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
      <li><a href="{{ route('refund') }}">Refund &amp; Cancellation</a></li>
      @auth
        <li><a href="{{ route('dashboard') }}"><i class="bi bi-person-circle"></i> My Account</a></li>
      @else
        <li><a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login / Register</a></li>
      @endauth
    </ul>
  </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const searchToggle = document.getElementById('floraSearchToggle');
  const searchModalBackdrop = document.getElementById('floraSearchModalBackdrop');
  const searchCloseBtn = document.getElementById('floraSearchCloseBtn');
  const searchInput = document.getElementById('floraSearchInput');

  function openSearchModal() {
    if (searchModalBackdrop) {
      searchModalBackdrop.classList.add('active');
      document.body.style.overflow = 'hidden';
      setTimeout(() => {
        if (searchInput) searchInput.focus();
      }, 100);
    }
  }

  function closeSearchModal() {
    if (searchModalBackdrop) {
      searchModalBackdrop.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  if (searchToggle) {
    searchToggle.addEventListener('click', function(e) {
      e.preventDefault();
      openSearchModal();
    });
  }

  if (searchCloseBtn) {
    searchCloseBtn.addEventListener('click', closeSearchModal);
  }

  if (searchModalBackdrop) {
    searchModalBackdrop.addEventListener('click', function(e) {
      if (e.target === searchModalBackdrop) {
        closeSearchModal();
      }
    });
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && searchModalBackdrop && searchModalBackdrop.classList.contains('active')) {
      closeSearchModal();
    }
  });

  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const q = this.value.trim().toLowerCase();
      const items = document.querySelectorAll('.flora-search-remedy-item');
      items.forEach(el => {
        const title = el.getAttribute('data-title') || '';
        if (!q || title.includes(q)) {
          el.style.display = 'flex';
        } else {
          el.style.display = 'none';
        }
      });
    });
  }

  const mobileToggle = document.getElementById('floraMobileToggle');
  const mobileDrawer = document.getElementById('floraMobileDrawer');
  if (mobileToggle && mobileDrawer) {
    mobileToggle.addEventListener('click', function() {
      mobileDrawer.classList.toggle('active');
    });
  }
});
</script>
