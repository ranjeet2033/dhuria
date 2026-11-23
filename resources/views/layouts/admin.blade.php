<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - Dhuria Ayurvedic Portal</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles / Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; }
        .font-serif { font-family: 'Playfair Display', Georgia, serif; }
        [x-cloak] { display: none !important; }
        .admin-nav-active {
            background: linear-gradient(135deg, #d4af37 0%, #b89127 100%) !important;
            color: #0b1320 !important;
            font-weight: 800 !important;
            box-shadow: 0 4px 15px -1px rgba(212, 175, 55, 0.35);
        }
        .admin-nav-active i {
            color: #0b1320 !important;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar (Desktop & Mobile drawer) -->
        <aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-[#0a111e] text-slate-300 border-r border-slate-800/80 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-auto lg:z-auto"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Logo Header -->
            <div class="flex items-center justify-between px-6 py-5 bg-[#070c16] border-b border-slate-800/80">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/dhuria_logo_white.png') }}" alt="Dhuria Logo" class="h-9 w-auto object-contain">
                    <div>
                        <span class="text-[9px] text-[#d4af37] uppercase font-extrabold tracking-widest block">Admin Portal</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3.5 py-5 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'admin-nav-active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-400"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.products.*') ? 'admin-nav-active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-box w-5 text-center text-slate-400"></i>
                    <span>Products</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.categories.*') ? 'admin-nav-active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-folder-tree w-5 text-center text-slate-400"></i>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.orders.*') ? 'admin-nav-active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-truck-fast w-5 text-center text-slate-400"></i>
                    <span>Orders</span>
                    @php
                        $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                        $pendingReturnsCount = \App\Models\Order::where('return_status', 'pending')->count();
                    @endphp
                    <div class="ml-auto flex items-center gap-1.5">
                        @if($pendingOrdersCount > 0)
                            <span class="bg-amber-500 text-slate-950 text-[10px] font-black px-1.5 py-0.2 rounded-full" title="Pending Orders">{{ $pendingOrdersCount }}</span>
                        @endif
                        @if($pendingReturnsCount > 0)
                            <span class="bg-rose-500 text-white text-[10px] font-black px-1.5 py-0.2 rounded-full animate-pulse" title="Return Requests">{{ $pendingReturnsCount }}</span>
                        @endif
                    </div>
                </a>

                <a href="{{ route('admin.testimonials.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.testimonials.*') ? 'admin-nav-active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-comment-dots w-5 text-center text-slate-400"></i>
                    <span>Testimonials</span>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'admin-nav-active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-center text-slate-400"></i>
                    <span>Customers</span>
                </a>

                <a href="{{ route('admin.banners.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.banners.*') ? 'admin-nav-active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-images w-5 text-center text-slate-400"></i>
                    <span>Banners</span>
                </a>

                <a href="{{ route('admin.coupons.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.coupons.*') ? 'admin-nav-active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-ticket w-5 text-center text-slate-400"></i>
                    <span>Coupons</span>
                </a>

                <a href="{{ route('admin.settings.edit') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.settings.*') ? 'admin-nav-active' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 text-center text-slate-400"></i>
                    <span>Settings</span>
                </a>

                <div class="pt-4 mt-4 border-t border-slate-800/80">
                    <a href="/" target="_blank"
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold hover:bg-white/5 hover:text-amber-400 transition-all text-slate-400">
                        <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center"></i>
                        <span>Live Storefront</span>
                    </a>
                </div>
            </nav>

            <!-- Bottom Profile / Logout -->
            <div class="p-3.5 bg-[#070c16] border-t border-slate-800/80">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <div class="bg-[#d4af37]/20 border border-[#d4af37]/30 text-[#d4af37] h-8 w-8 rounded-lg flex items-center justify-center font-black text-xs">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <span class="block text-xs font-bold text-white truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                            <span class="block text-[10px] text-slate-400 truncate max-w-[120px]">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-white/5 transition cursor-pointer" title="Log Out">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile drawer -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            <!-- Top Header -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-slate-200/80 shadow-xs">
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-600 hover:text-slate-900 mr-2 p-1.5 rounded-lg hover:bg-slate-100 transition">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">
                            @yield('header_title', 'Overview')
                        </h2>
                        <span class="text-[10px] text-slate-400 font-medium block sm:hidden">{{ date('d M Y') }}</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-xs text-slate-400 font-medium hidden sm:inline"><i class="fa-regular fa-calendar mr-1.5 text-slate-400"></i>{{ date('l, d M Y') }}</span>
                    <div class="h-5 w-[1px] bg-slate-200 hidden sm:block"></div>
                    <a href="/" target="_blank" class="text-xs font-bold text-slate-700 hover:text-amber-600 bg-amber-50 hover:bg-amber-100/70 border border-amber-200/80 px-3 py-1.5 rounded-lg transition flex items-center space-x-1.5 shadow-2xs">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-amber-600"></i>
                        <span>Live Site</span>
                    </a>
                </div>
            </header>

            <!-- Main Scrollable Section -->
            <main class="flex-1 overflow-y-auto px-6 py-8">
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center space-x-3 text-emerald-800 shadow-sm animate-fade-in" x-data="{ show: true }" x-show="show">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                        <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center space-x-3 text-rose-800 shadow-sm animate-fade-in" x-data="{ show: true }" x-show="show">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
                        <span class="text-sm font-semibold">{{ session('error') }}</span>
                        <button @click="show = false" class="ml-auto text-rose-400 hover:text-rose-600"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

    @stack('scripts')
</body>
</html>
