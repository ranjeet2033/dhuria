@extends('layouts.frontend')

@section('title', 'My Account - Dhuria')

@section('content')
@php
    $defaultTab = session('tab') ?? request('tab') ?? (
        session('status') === 'password-updated' || $errors->updatePassword->any() ? 'settings' : (
        session('status') === 'profile-updated' ? 'profile' : (
        session('status') === 'address-added' || session('status') === 'address-updated' ? 'addresses' : 'profile'
    )));
@endphp

<div class="bg-slate-50/60 py-6 md:py-10 min-h-screen" 
     x-data="{ 
         activeTab: '{{ $defaultTab }}',
         showAddAddress: false,
         editingAddress: null,
         cancellingOrder: null,
         cancellationReason: 'Ordered by mistake',
         cancellationComments: '',
         returningOrder: null,
         returnType: 'return',
         returnReason: 'Damaged or defective packaging upon delivery',
         returnComments: '',
         refundAccountDetails: '',
         filterOrderStatus: 'all',
         switchTab(tab) {
             this.activeTab = tab;
             if (window.history.replaceState) {
                 const url = new URL(window.location);
                 url.searchParams.set('tab', tab);
                 window.history.replaceState({}, '', url);
             }
         }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Bar -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-[#d4af37] mb-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-[#d4af37]"></span>
                    <span>AUTHENTIC AYURVEDA PORTAL</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#081a30] tracking-tight">
                    Customer Account
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Welcome back, <strong class="text-slate-900 font-bold">{{ Auth::user()->name }}</strong>. Manage orders, dispatch tracking, addresses and personal details.
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ url('/shop') }}" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-[#081a30] border border-slate-200 px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-2xs">
                    <i class="fa-solid fa-basket-shopping text-[#d4af37]"></i> Shop Formulations
                </a>
                <a href="{{ url('/cart') }}" class="inline-flex items-center gap-2 bg-[#081a30] hover:bg-[#0c2544] text-white px-3.5 py-2 rounded-xl text-xs font-bold transition shadow-sm">
                    <i class="fa-solid fa-bag-shopping text-[#d4af37]"></i> Bag ({{ $cartCount ?? 0 }})
                </a>
            </div>
        </div>

        <!-- Global Flash Alerts -->
        @if (session('status') === 'profile-updated')
            <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-xs font-semibold shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>Your profile details have been successfully updated in our system.</span>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-xs font-semibold shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>Your password has been changed successfully. Your account is secure.</span>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-xs font-semibold shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl px-4 py-3 text-xs font-semibold shadow-xs">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->updatePassword->any())
            <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl px-4 py-3 text-xs shadow-xs">
                <div class="font-bold mb-1 flex items-center gap-2 text-rose-800">
                    <i class="fa-solid fa-triangle-exclamation"></i> Password change failed:
                </div>
                <ul class="list-disc list-inside space-y-0.5 ml-2">
                    @foreach ($errors->updatePassword->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @elseif ($errors->any())
            <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl px-4 py-3 text-xs shadow-xs">
                <div class="font-bold mb-1 flex items-center gap-2 text-rose-800">
                    <i class="fa-solid fa-triangle-exclamation"></i> Action could not be processed:
                </div>
                <ul class="list-disc list-inside space-y-0.5 ml-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mobile Horizontal Tab Pills -->
        <div class="lg:hidden mb-5 -mx-4 px-4 overflow-x-auto scrollbar-none">
            <div class="flex items-center gap-2 min-w-max pb-1">
                <button type="button" @click="switchTab('profile')"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition cursor-pointer"
                        :class="activeTab === 'profile' ? 'bg-[#081a30] text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700'">
                    <i class="fa-solid fa-user text-xs"></i>
                    <span>Profile</span>
                </button>

                <button type="button" @click="switchTab('orders')"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition cursor-pointer"
                        :class="activeTab === 'orders' ? 'bg-[#081a30] text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700'">
                    <i class="fa-solid fa-box-archive text-xs"></i>
                    <span>My Orders</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-black"
                          :class="activeTab === 'orders' ? 'bg-[#d4af37] text-slate-950' : 'bg-slate-100 text-slate-700'">
                        {{ count($orders) }}
                    </span>
                </button>

                <button type="button" @click="switchTab('addresses')"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition cursor-pointer"
                        :class="activeTab === 'addresses' ? 'bg-[#081a30] text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700'">
                    <i class="fa-solid fa-map-location-dot text-xs"></i>
                    <span>Addresses</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-black"
                          :class="activeTab === 'addresses' ? 'bg-[#d4af37] text-slate-950' : 'bg-slate-100 text-slate-700'">
                        {{ count($addresses) }}
                    </span>
                </button>

                <button type="button" @click="switchTab('settings')"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition cursor-pointer"
                        :class="activeTab === 'settings' ? 'bg-[#081a30] text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700'">
                    <i class="fa-solid fa-lock text-xs"></i>
                    <span>Security</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Sidebar Card (Compact, Professional & High-Contrast) -->
            <div class="lg:col-span-3 space-y-4">
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs">
                    
                    <!-- Customer Avatar & Info -->
                    <div class="text-center pb-5 border-b border-slate-100">
                        <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-[#081a30] to-[#163a63] text-white flex items-center justify-center font-black text-xl mx-auto shadow-sm mb-2.5 border-2 border-[#d4af37]/40">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center text-[8px] text-white" title="Active Account">
                                <i class="fa-solid fa-check"></i>
                            </span>
                        </div>
                        <h2 class="text-sm font-extrabold text-[#081a30] leading-tight truncate px-2">{{ Auth::user()->name }}</h2>
                        <p class="text-[11px] text-slate-500 mt-0.5 truncate px-2">{{ Auth::user()->email }}</p>
                        @if(Auth::user()->phone)
                            <p class="text-[11px] text-slate-600 font-bold mt-1 flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-phone text-[9px] text-[#d4af37]"></i> {{ Auth::user()->phone }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-center gap-2 mt-2.5">
                            <span class="inline-flex items-center gap-1 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-[9px] px-2 py-0.5 rounded-full uppercase tracking-wider">
                                <i class="fa-solid fa-shield-heart text-emerald-600"></i> Verified Customer
                            </span>
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-[9px] uppercase tracking-wider px-2 py-0.5 rounded-full transition shadow-2xs">
                                    Admin
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar Navigation Items -->
                    <nav class="space-y-1 text-xs font-bold pt-3">
                        <button type="button" @click="switchTab('profile')"
                                class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition text-left cursor-pointer group"
                                :class="activeTab === 'profile' ? 'bg-[#081a30] text-white shadow-xs' : 'text-slate-700 hover:bg-slate-50'">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-user text-xs w-4 text-center" :class="activeTab === 'profile' ? 'text-[#d4af37]' : 'text-slate-400 group-hover:text-slate-700'"></i>
                                <span>Personal Details</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[9px]" :class="activeTab === 'profile' ? 'text-[#d4af37]' : 'text-slate-300'"></i>
                        </button>

                        <button type="button" @click="switchTab('orders')"
                                class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition text-left cursor-pointer group"
                                :class="activeTab === 'orders' ? 'bg-[#081a30] text-white shadow-xs' : 'text-slate-700 hover:bg-slate-50'">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-box-archive text-xs w-4 text-center" :class="activeTab === 'orders' ? 'text-[#d4af37]' : 'text-slate-400 group-hover:text-slate-700'"></i>
                                <span>My Orders</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-black"
                                      :class="activeTab === 'orders' ? 'bg-[#d4af37] text-slate-950' : 'bg-slate-100 text-slate-700'">
                                    {{ count($orders) }}
                                </span>
                                <i class="fa-solid fa-chevron-right text-[9px]" :class="activeTab === 'orders' ? 'text-[#d4af37]' : 'text-slate-300'"></i>
                            </div>
                        </button>

                        <button type="button" @click="switchTab('addresses')"
                                class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition text-left cursor-pointer group"
                                :class="activeTab === 'addresses' ? 'bg-[#081a30] text-white shadow-xs' : 'text-slate-700 hover:bg-slate-50'">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-map-location-dot text-xs w-4 text-center" :class="activeTab === 'addresses' ? 'text-[#d4af37]' : 'text-slate-400 group-hover:text-slate-700'"></i>
                                <span>Saved Addresses</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-black"
                                      :class="activeTab === 'addresses' ? 'bg-[#d4af37] text-slate-950' : 'bg-slate-100 text-slate-700'">
                                    {{ count($addresses) }}
                                </span>
                                <i class="fa-solid fa-chevron-right text-[9px]" :class="activeTab === 'addresses' ? 'text-[#d4af37]' : 'text-slate-300'"></i>
                            </div>
                        </button>

                        <button type="button" @click="switchTab('settings')"
                                class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition text-left cursor-pointer group"
                                :class="activeTab === 'settings' ? 'bg-[#081a30] text-white shadow-xs' : 'text-slate-700 hover:bg-slate-50'">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-lock text-xs w-4 text-center" :class="activeTab === 'settings' ? 'text-[#d4af37]' : 'text-slate-400 group-hover:text-slate-700'"></i>
                                <span>Account Security</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[9px]" :class="activeTab === 'settings' ? 'text-[#d4af37]' : 'text-slate-300'"></i>
                        </button>

                        <div class="pt-2 border-t border-slate-100 mt-2">
                            <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50 font-bold transition text-left cursor-pointer">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-xs w-4 text-center"></i>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </nav>

                </div>

                <!-- Care Support Box -->
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs">
                    <h3 class="text-xs font-black uppercase tracking-wider text-[#081a30] mb-1.5 flex items-center gap-2">
                        <i class="fa-solid fa-headset text-[#d4af37]"></i> Need Order Assistance?
                    </h3>
                    <p class="text-[11px] text-slate-500 mb-2.5 leading-relaxed">
                        For dispatch tracking, Ayurvedic consultations, or cancellation/return queries:
                    </p>
                    <a href="https://wa.me/919220251059" target="_blank" class="flex items-center gap-2 text-emerald-700 bg-emerald-50 hover:bg-emerald-100/70 p-2 rounded-xl text-xs font-bold transition">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>WhatsApp: +91 9220251059</span>
                    </a>
                </div>

            </div>

            <!-- Right Main Work Area -->
            <div class="lg:col-span-9">
                
                <!-- ==============================================
                     TAB 1: Profile (Personal Information)
                     ============================================== -->
                <div x-show="activeTab === 'profile'" class="space-y-5">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-7 shadow-xs">
                        <div class="pb-3 mb-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <h2 class="text-lg font-extrabold text-[#081a30]">Personal Details</h2>
                                <p class="text-xs text-slate-500">Update your verified contact information for delivery SMS and notifications.</p>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-0.5 rounded-full">
                                Customer ID: #{{ Auth::id() }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                            @csrf
                            @method('patch')

                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                    Full Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                       class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:outline-none focus:border-[#081a30] focus:bg-white focus:ring-1 focus:ring-[#081a30] transition">
                                @error('name')
                                    <p class="text-rose-600 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                        Email Address <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                           class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:outline-none focus:border-[#081a30] focus:bg-white focus:ring-1 focus:ring-[#081a30] transition">
                                    @error('email')
                                        <p class="text-rose-600 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                        Phone Number <span class="text-slate-400 font-normal">(for delivery SMS/calls)</span>
                                    </label>
                                    <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="e.g. 9876543210"
                                           class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:outline-none focus:border-[#081a30] focus:bg-white focus:ring-1 focus:ring-[#081a30] transition">
                                    @error('phone')
                                        <p class="text-rose-600 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                                <button type="submit" 
                                        class="bg-[#081a30] hover:bg-[#0c2544] text-white font-black px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shadow-sm cursor-pointer inline-flex items-center gap-2">
                                    <i class="fa-solid fa-floppy-disk text-[#d4af37]"></i> SAVE CHANGES
                                </button>
                                <span class="text-[11px] text-slate-400">
                                    Member since {{ Auth::user()->created_at->format('M Y') }}
                                </span>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Account Stats -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                        <div class="bg-white border border-slate-200/90 rounded-xl p-3.5 text-center shadow-2xs">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Total Orders</span>
                            <span class="text-xl font-black text-[#081a30] mt-0.5 block">{{ count($orders) }}</span>
                        </div>
                        <div class="bg-white border border-slate-200/90 rounded-xl p-3.5 text-center shadow-2xs">
                            <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider block">In Progress</span>
                            <span class="text-xl font-black text-amber-600 mt-0.5 block">{{ $activeOrders->count() }}</span>
                        </div>
                        <div class="bg-white border border-slate-200/90 rounded-xl p-3.5 text-center shadow-2xs">
                            <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider block">Delivered</span>
                            <span class="text-xl font-black text-emerald-600 mt-0.5 block">{{ $orders->whereIn('status', ['delivered', 'completed'])->count() }}</span>
                        </div>
                        <div class="bg-white border border-slate-200/90 rounded-xl p-3.5 text-center shadow-2xs">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Total Spent</span>
                            <span class="text-xl font-black text-[#081a30] mt-0.5 block">&#8377;{{ number_format($orders->where('payment_status', 'completed')->sum('total_amount'), 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- ==============================================
                     TAB 2: My Orders (Full Redesign + Cancellation & Returns)
                     ============================================== -->
                <div x-show="activeTab === 'orders'" class="space-y-4" style="display: none;">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-xs">
                        
                        <!-- Header & Status Filter Bar -->
                        <div class="pb-4 mb-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2.5">
                            <div>
                                <h2 class="text-lg font-extrabold text-[#081a30]">Your Orders</h2>
                                <p class="text-xs text-slate-500">Track shipments, download invoices, request cancellations or exchanges.</p>
                            </div>

                            <!-- Filter Pills -->
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <button type="button" @click="filterOrderStatus = 'all'"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition cursor-pointer"
                                        :class="filterOrderStatus === 'all' ? 'bg-[#081a30] text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                    All ({{ count($orders) }})
                                </button>
                                <button type="button" @click="filterOrderStatus = 'active'"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition cursor-pointer"
                                        :class="filterOrderStatus === 'active' ? 'bg-amber-500 text-slate-950 font-black shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                    Active ({{ $activeOrders->count() }})
                                </button>
                                <button type="button" @click="filterOrderStatus = 'completed'"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition cursor-pointer"
                                        :class="filterOrderStatus === 'completed' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                    Delivered ({{ $orders->whereIn('status', ['delivered', 'completed'])->count() }})
                                </button>
                                <button type="button" @click="filterOrderStatus = 'cancelled'"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition cursor-pointer"
                                        :class="filterOrderStatus === 'cancelled' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                                    Cancelled ({{ $orders->where('status', 'cancelled')->count() }})
                                </button>
                            </div>
                        </div>

                        @if($orders->isEmpty())
                            <div class="text-center py-10 px-4 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                <div class="w-14 h-14 rounded-xl bg-white border border-slate-200 text-slate-300 flex items-center justify-center text-xl mx-auto mb-2.5 shadow-2xs">
                                    <i class="fa-solid fa-box-open text-slate-400"></i>
                                </div>
                                <h3 class="text-sm font-extrabold text-[#081a30]">No Orders Placed Yet</h3>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-0.5 mb-4 leading-relaxed">
                                    Discover pure Ayurvedic wellness formulations including Diabetes Pro MadhuCurex, Liver Detox capsules, and revitalizing churna kits.
                                </p>
                                <a href="{{ url('/shop') }}" class="inline-flex items-center gap-2 bg-[#081a30] hover:bg-[#0c2544] text-[#d4af37] font-black px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider shadow-sm transition">
                                    <i class="fa-solid fa-basket-shopping"></i> Explore Shop
                                </a>
                            </div>
                        @else
                            <div class="space-y-3.5">
                                @foreach($orders as $ord)
                                    @php
                                        $isOrdActive = in_array($ord->status, ['pending', 'processing', 'shipped']);
                                        $isOrdDelivered = in_array($ord->status, ['delivered', 'completed']);
                                        $isOrdCancelled = $ord->status === 'cancelled';
                                        $isCancellable = in_array($ord->status, ['pending', 'processing']);
                                        $isReturnable = in_array($ord->status, ['delivered', 'completed']) && empty($ord->return_status);
                                    @endphp
                                    <div x-show="filterOrderStatus === 'all' || 
                                                (filterOrderStatus === 'active' && {{ $isOrdActive ? 'true' : 'false' }}) || 
                                                (filterOrderStatus === 'completed' && {{ $isOrdDelivered ? 'true' : 'false' }}) || 
                                                (filterOrderStatus === 'cancelled' && {{ $isOrdCancelled ? 'true' : 'false' }})"
                                         x-data="{ expanded: false }"
                                         class="border border-slate-200/90 rounded-xl bg-white hover:border-slate-300 transition shadow-2xs overflow-hidden">
                                        
                                        <!-- Order Top Bar -->
                                        <div class="p-3.5 sm:p-4 bg-slate-50/70 border-b border-slate-200/80 flex items-center justify-between flex-wrap gap-2.5">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-lg bg-[#081a30] text-[#d4af37] flex items-center justify-center font-bold text-xs shadow-2xs flex-shrink-0">
                                                    <i class="fa-solid fa-receipt"></i>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-xs font-black text-[#081a30]">#{{ $ord->order_number ?? $ord->id }}</span>
                                                        <button type="button" @click.stop="navigator.clipboard.writeText('{{ $ord->order_number ?? $ord->id }}')" 
                                                                class="text-slate-400 hover:text-[#081a30] transition text-[10px]" title="Copy Order Number">
                                                            <i class="fa-regular fa-copy"></i>
                                                        </button>
                                                    </div>
                                                    <span class="text-[10px] text-slate-500 font-medium block">
                                                        {{ $ord->created_at->format('d M Y, h:i A') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2.5 flex-wrap">
                                                <!-- Status Badges -->
                                                @if($ord->return_status === 'pending')
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300">
                                                        <i class="fa-solid fa-arrows-rotate fa-spin text-[8px]"></i> Return Requested
                                                    </span>
                                                @elseif($ord->return_status === 'approved')
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                        <i class="fa-solid fa-check text-[8px]"></i> Return Approved
                                                    </span>
                                                @elseif($ord->return_status === 'rejected')
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-300">
                                                        <i class="fa-solid fa-xmark text-[8px]"></i> Return Rejected
                                                    </span>
                                                @elseif($ord->status === 'delivered' || $ord->status === 'completed')
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        <i class="fa-solid fa-circle-check text-[8px]"></i> Delivered
                                                    </span>
                                                @elseif($ord->status === 'cancelled')
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-200">
                                                        <i class="fa-solid fa-ban text-[8px]"></i> Cancelled
                                                    </span>
                                                @elseif($ord->status === 'shipped')
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                        <i class="fa-solid fa-truck-fast text-[8px]"></i> In Transit (Shipped)
                                                    </span>
                                                @elseif($ord->status === 'processing')
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 border border-sky-200">
                                                        <i class="fa-solid fa-arrows-rotate fa-spin text-[8px]"></i> Processing
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-200">
                                                        <i class="fa-solid fa-clock text-[8px]"></i> Pending
                                                    </span>
                                                @endif

                                                <div class="text-right">
                                                    <span class="text-xs font-black text-[#081a30]">&#8377;{{ number_format($ord->total_amount, 2) }}</span>
                                                    <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider">{{ strtoupper($ord->payment_method ?? 'COD') }}</span>
                                                </div>

                                                <button type="button" @click="expanded = !expanded"
                                                        class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                                                    <span x-text="expanded ? 'Hide Details' : 'View Details'"></span>
                                                    <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Order Items (Clear Thumbnails & Details) -->
                                        <div class="p-3.5 sm:p-4 divide-y divide-slate-100">
                                            @foreach($ord->items as $item)
                                                @php
                                                    $itemPrice = (float)($item->unit_price ?: 0);
                                                    $itemTotal = (float)($item->total_price ?: ($itemPrice * $item->quantity));
                                                @endphp
                                                <div class="flex items-center justify-between py-2 first:pt-0 last:pb-0 gap-3">
                                                    <div class="flex items-center gap-3 min-w-0">
                                                        <div class="w-14 h-14 rounded-xl bg-slate-50 border border-slate-200 p-1 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                            <img src="{{ $item->product ? $item->product->primary_image_url : asset('images/card_diabetes_pro_hd.jpg') }}" 
                                                                 alt="{{ $item->product_name }}" 
                                                                 class="max-w-full max-h-full object-contain">
                                                        </div>
                                                        <div class="min-w-0">
                                                            <h4 class="text-xs font-extrabold text-slate-900 truncate">
                                                                {{ $item->product_name ?? ($item->product ? $item->product->name : 'Ayurvedic Product') }}
                                                            </h4>
                                                            <div class="text-[11px] text-slate-500 font-semibold mt-0.5">
                                                                Quantity: <strong class="text-slate-800">{{ $item->quantity }}</strong> 
                                                                <span class="mx-1 text-slate-300">&bull;</span> 
                                                                Price: <strong class="text-slate-800">&#8377;{{ number_format($itemPrice, 2) }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right flex-shrink-0 font-sans">
                                                        <span class="text-xs font-black text-[#081a30]">&#8377;{{ number_format($itemTotal, 2) }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Expandable Order Deep Details (Alpine Collapse) -->
                                        <div x-show="expanded" x-collapse class="border-t border-slate-100 bg-[#f8fbfe] p-4 sm:p-5 space-y-4">
                                            
                                            <!-- 4-Grid Breakdown: Delivery, Logistics, Payment, Financial Summary -->
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                                                
                                                <!-- 1. Delivery Information -->
                                                <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                                                    <div>
                                                        <h5 class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5 flex items-center gap-1">
                                                            <i class="fa-solid fa-location-dot text-[#d4af37]"></i> Delivery Address
                                                        </h5>
                                                        <p class="font-extrabold text-slate-900 leading-snug">{{ $ord->shipping_name ?: Auth::user()->name }}</p>
                                                        <p class="text-slate-600 mt-1 leading-snug text-[11px]">
                                                            {{ $ord->shipping_address }}
                                                            @if($ord->shipping_city || $ord->shipping_state)
                                                                <br>{{ $ord->shipping_city }}, {{ $ord->shipping_state }} - {{ $ord->shipping_zip }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @if($ord->shipping_phone)
                                                        <p class="text-[10px] text-slate-500 font-bold mt-2 pt-1 border-t border-slate-100">
                                                            <i class="fa-solid fa-phone text-[9px] mr-1 text-slate-400"></i> {{ $ord->shipping_phone }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <!-- 2. Shipping & Logistics -->
                                                <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                                                    <div>
                                                        <h5 class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5 flex items-center gap-1">
                                                            <i class="fa-solid fa-truck-fast text-[#d4af37]"></i> Shipment & Tracking
                                                        </h5>
                                                        <div class="space-y-1 text-[11px] text-slate-600">
                                                            <div class="flex justify-between">
                                                                <span>Courier:</span>
                                                                <span class="font-bold text-slate-900">{{ $ord->shiprocket_order_id ? 'Shiprocket Express' : 'Standard Surface' }}</span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span>Status:</span>
                                                                <span class="font-bold text-slate-900">{{ ucfirst($ord->shiprocket_status ?: $ord->status) }}</span>
                                                            </div>
                                                            @if($ord->shiprocket_awb_code)
                                                                <div class="pt-1 border-t border-slate-100 flex justify-between items-center">
                                                                    <span>AWB:</span>
                                                                    <span class="font-mono font-black text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded text-[10px] select-all">{{ $ord->shiprocket_awb_code }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($ord->shiprocket_awb_code)
                                                        <a href="https://shiprocket.co/tracking/{{ $ord->shiprocket_awb_code }}" target="_blank" 
                                                           class="mt-2 text-[10px] font-black text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                                            <span>Live Courier Tracking</span> <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                                                        </a>
                                                    @endif
                                                </div>

                                                <!-- 3. Payment Details -->
                                                <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs flex flex-col justify-between">
                                                    <div>
                                                        <h5 class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5 flex items-center gap-1">
                                                            <i class="fa-solid fa-credit-card text-[#d4af37]"></i> Payment Info
                                                        </h5>
                                                        <div class="space-y-1 text-[11px] text-slate-600">
                                                            <div class="flex justify-between">
                                                                <span>Method:</span>
                                                                <span class="font-bold text-slate-900 uppercase">{{ $ord->payment_method === 'cod' ? 'Cash on Delivery' : strtoupper($ord->payment_method) }}</span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span>Status:</span>
                                                                <span class="font-bold {{ $ord->payment_status === 'completed' ? 'text-emerald-600' : ($ord->payment_status === 'refunded' ? 'text-purple-600' : 'text-amber-600') }}">
                                                                    {{ ucfirst($ord->payment_status ?? 'Pending') }}
                                                                </span>
                                                            </div>
                                                            @if($ord->razorpay_payment_id)
                                                                <div class="pt-1 border-t border-slate-100">
                                                                    <span class="text-[10px] text-slate-400 block">Payment ID:</span>
                                                                    <span class="font-mono text-[10px] text-slate-800 font-bold truncate block select-all">{{ $ord->razorpay_payment_id }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($ord->refund_id)
                                                        <p class="text-[10px] font-bold text-emerald-700 bg-emerald-50 p-1 rounded mt-1">
                                                            Refund Ref: {{ $ord->refund_id }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <!-- 4. Order Financial Summary -->
                                                <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs">
                                                    <h5 class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1.5 flex items-center gap-1">
                                                        <i class="fa-solid fa-calculator text-[#d4af37]"></i> Summary
                                                    </h5>
                                                    <div class="space-y-1 text-[11px] text-slate-600">
                                                        <div class="flex justify-between">
                                                            <span>Items Subtotal:</span>
                                                            <span class="font-bold text-slate-800">&#8377;{{ number_format($ord->items->sum('total_price') ?: $ord->total_amount, 2) }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span>Express Shipping:</span>
                                                            <span class="font-bold text-emerald-600 uppercase">FREE</span>
                                                        </div>
                                                        <div class="flex justify-between pt-1 border-t border-slate-100">
                                                            <span class="font-extrabold text-slate-900">Total Paid:</span>
                                                            <span class="font-black text-[#081a30] text-xs">&#8377;{{ number_format($ord->total_amount, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Cancellation Reason Alert if Cancelled -->
                                            @if($ord->status === 'cancelled')
                                                <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 text-xs text-rose-800 space-y-1">
                                                    <div class="font-bold flex items-center gap-1.5 text-rose-900">
                                                        <i class="fa-solid fa-ban text-rose-600"></i> Order Cancelled
                                                        @if($ord->cancelled_at)
                                                            <span class="text-[10px] font-normal text-rose-600">({{ \Carbon\Carbon::parse($ord->cancelled_at)->format('d M Y, h:i A') }})</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[11px] text-rose-700">
                                                        Reason: <strong class="text-rose-950 font-semibold">{{ $ord->cancellation_reason ?: 'Cancelled by customer' }}</strong>
                                                    </p>
                                                    @if($ord->refund_id)
                                                        <p class="text-[11px] text-emerald-800 font-bold mt-1">
                                                            <i class="fa-solid fa-check text-emerald-600"></i> Refund of &#8377;{{ number_format($ord->refund_amount ?: $ord->total_amount, 2) }} processed to original payment method (Ref: {{ $ord->refund_id }}).
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Return Status Alert if Return Active -->
                                            @if($ord->return_status)
                                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-900 space-y-1">
                                                    <div class="font-bold flex items-center justify-between">
                                                        <span class="flex items-center gap-1.5">
                                                            <i class="fa-solid fa-arrows-rotate text-amber-600"></i> {{ ucfirst($ord->return_type ?: 'Return') }} Request
                                                        </span>
                                                        <span class="text-[9px] uppercase font-black px-2 py-0.5 rounded-full
                                                            @if($ord->return_status === 'approved') bg-emerald-100 text-emerald-800
                                                            @elseif($ord->return_status === 'rejected') bg-rose-100 text-rose-800
                                                            @else bg-amber-200 text-amber-900 @endif">
                                                            {{ ucfirst($ord->return_status) }}
                                                        </span>
                                                    </div>
                                                    <p class="text-[11px]">Reason: <strong class="text-slate-900">{{ $ord->return_reason }}</strong></p>
                                                    @if($ord->return_comments)
                                                        <p class="text-[10px] text-slate-600 italic">"{{ $ord->return_comments }}"</p>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Bottom Action Bar: Invoice, Cancel, Return -->
                                            <div class="pt-2 border-t border-slate-200/80 flex items-center justify-between flex-wrap gap-2.5">
                                                <div class="flex items-center gap-2">
                                                    <!-- Direct Invoice Link -->
                                                    <a href="{{ route('checkout.success', $ord->order_number ?? $ord->id) }}" 
                                                       target="_blank"
                                                       class="inline-flex items-center gap-1.5 bg-white hover:bg-slate-100 text-[#081a30] border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-2xs">
                                                        <i class="fa-solid fa-file-invoice text-[#d4af37]"></i> Invoice / Receipt
                                                    </a>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <!-- Cancel Order Button (Available for Pending/Processing orders) -->
                                                    @if($isCancellable)
                                                        <button type="button" 
                                                                @click="cancellingOrder = {{ json_encode($ord) }}; cancellationReason = 'Ordered by mistake'; cancellationComments = ''"
                                                                class="inline-flex items-center gap-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer">
                                                            <i class="fa-solid fa-xmark text-xs"></i> Cancel Order
                                                        </button>
                                                    @endif

                                                    <!-- Return / Replace Button (Available for Delivered orders within 7 days) -->
                                                    @if($isReturnable)
                                                        <button type="button" 
                                                                @click="returningOrder = {{ json_encode($ord) }}; returnType = 'return'; returnReason = 'Damaged or defective packaging upon delivery'; returnComments = ''; refundAccountDetails = ''"
                                                                class="inline-flex items-center gap-1.5 bg-[#081a30] hover:bg-[#0c2544] text-[#d4af37] px-3.5 py-1.5 rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                                                            <i class="fa-solid fa-arrow-rotate-left text-xs"></i> Return / Replace
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>

                <!-- ==============================================
                     TAB 3: Saved Addresses
                     ============================================== -->
                <div x-show="activeTab === 'addresses'" class="space-y-4" style="display: none;">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-xs">
                        
                        <div class="pb-3 mb-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2.5">
                            <div>
                                <h2 class="text-lg font-extrabold text-[#081a30]">Saved Delivery Addresses</h2>
                                <p class="text-xs text-slate-500">Manage delivery locations for 1-click Ayurvedic checkout.</p>
                            </div>

                            <button type="button" @click="showAddAddress = !showAddAddress"
                                    class="inline-flex items-center gap-1.5 bg-[#081a30] hover:bg-[#0c2544] text-[#d4af37] font-black px-3.5 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-sm cursor-pointer">
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span x-text="showAddAddress ? 'Close' : '+ Add Address'"></span>
                            </button>
                        </div>

                        <!-- Add Address Form -->
                        <div x-show="showAddAddress" x-collapse class="mb-5 p-4 sm:p-5 bg-slate-50/80 rounded-xl border border-slate-200 shadow-2xs">
                            <h3 class="text-xs font-black uppercase tracking-wider text-[#081a30] mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-map-pin text-[#d4af37]"></i> Add New Destination
                            </h3>

                            <form method="POST" action="{{ route('addresses.store') }}" class="space-y-3">
                                @csrf

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                            Street Address / Building <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="address" required placeholder="House / Flat / Street / Landmark"
                                               class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] transition">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                            Apartment, Suite, Unit <span class="text-slate-400 font-normal">(optional)</span>
                                        </label>
                                        <input type="text" name="address2" placeholder="e.g. Floor 2, Suite 104"
                                               class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] transition">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">City *</label>
                                        <input type="text" name="city" required placeholder="e.g. Delhi"
                                               class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] transition">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">State *</label>
                                        <input type="text" name="state" required placeholder="e.g. Delhi"
                                               class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] transition">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">Pincode *</label>
                                        <input type="text" name="zip" required placeholder="e.g. 110001"
                                               class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] transition">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">Phone *</label>
                                        <input type="tel" name="phone" value="{{ Auth::user()->phone }}" required placeholder="10-digit mobile number"
                                               class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] transition">
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <input type="checkbox" name="is_default" id="is_default_new" value="1" class="rounded border-slate-300 text-[#081a30] focus:ring-[#081a30]">
                                    <label for="is_default_new" class="text-xs text-slate-700 font-bold select-none cursor-pointer">
                                        Set as default shipping address
                                    </label>
                                </div>

                                <div class="pt-2 flex items-center gap-2">
                                    <button type="submit" 
                                            class="bg-[#081a30] hover:bg-[#0c2544] text-[#d4af37] font-black px-5 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-sm cursor-pointer">
                                        Save Address
                                    </button>
                                    <button type="button" @click="showAddAddress = false" class="text-xs font-bold text-slate-500 hover:text-slate-800 px-3 py-2">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Saved Addresses Grid -->
                        @if($addresses->isEmpty())
                            <div class="text-center py-8 px-4 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                <i class="fa-solid fa-map-location-dot text-2xl text-slate-300 mb-1.5"></i>
                                <h4 class="text-xs font-extrabold text-[#081a30]">No Saved Addresses</h4>
                                <p class="text-xs text-slate-500 mt-0.5 mb-2.5">Add your delivery destination to speed up order checkouts.</p>
                                <button type="button" @click="showAddAddress = true" class="inline-flex items-center gap-1 text-xs font-bold text-[#081a30] hover:underline">
                                    <i class="fa-solid fa-plus text-[9px]"></i> Add Address Now
                                </button>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                @foreach($addresses as $addr)
                                    <div class="border {{ $addr->is_default ? 'border-[#081a30] bg-sky-50/25' : 'border-slate-200 bg-white' }} rounded-xl p-4 relative shadow-2xs transition flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between mb-1.5">
                                                <span class="text-xs font-black text-slate-900">Address #{{ $loop->iteration }}</span>
                                                @if($addr->is_default)
                                                    <span class="bg-[#081a30] text-[#d4af37] font-black text-[9px] uppercase tracking-wider px-2 py-0.5 rounded-full">
                                                        DEFAULT
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-xs font-extrabold text-slate-900 leading-snug">
                                                {{ $addr->address }}@if($addr->address2), {{ $addr->address2 }}@endif
                                            </p>
                                            <p class="text-xs text-slate-600 mt-0.5">
                                                {{ $addr->city }}, {{ $addr->state }} - {{ $addr->zip }}
                                            </p>
                                            <p class="text-[11px] text-slate-500 font-semibold mt-1.5">
                                                <i class="fa-solid fa-phone text-[9px] text-slate-400 mr-1"></i> {{ $addr->phone }}
                                            </p>
                                        </div>

                                        <div class="mt-3.5 pt-2.5 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2 text-xs">
                                            <div class="flex items-center gap-2.5">
                                                @if(!$addr->is_default)
                                                    <form method="POST" action="{{ route('addresses.setDefault', $addr->id) }}">
                                                        @csrf
                                                        @method('patch')
                                                        <button type="submit" class="text-xs font-bold text-[#081a30] hover:underline cursor-pointer">
                                                            Set Default
                                                        </button>
                                                    </form>
                                                @endif

                                                <button type="button" 
                                                        @click="editingAddress = {{ json_encode($addr) }}"
                                                        class="text-xs font-bold text-sky-700 hover:text-sky-900 flex items-center gap-1 cursor-pointer">
                                                    <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                                                </button>
                                            </div>

                                            <form method="POST" action="{{ route('addresses.destroy', $addr->id) }}"
                                                  onsubmit="return confirm('Are you sure you want to remove this address?');">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 flex items-center gap-1 cursor-pointer">
                                                    <i class="fa-regular fa-trash-can text-[11px]"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>

                <!-- ==============================================
                     TAB 4: Account Security
                     ============================================== -->
                <div x-show="activeTab === 'settings'" class="space-y-4" style="display: none;">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-7 shadow-xs">
                        <div class="pb-3 mb-4 border-b border-slate-100">
                            <h2 class="text-lg font-extrabold text-[#081a30]">Account Security</h2>
                            <p class="text-xs text-slate-500">Update your password regularly to protect your account and delivery records.</p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')

                            <div>
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                    Current Password <span class="text-rose-500">*</span>
                                </label>
                                <input type="password" name="current_password" required autocomplete="current-password"
                                       class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] transition">
                                @if($errors->updatePassword->has('current_password'))
                                    <p class="text-rose-600 text-[11px] mt-1 font-semibold">{{ $errors->updatePassword->first('current_password') }}</p>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                        New Password <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="password" name="password" required autocomplete="new-password"
                                           class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] transition">
                                    @if($errors->updatePassword->has('password'))
                                        <p class="text-rose-600 text-[11px] mt-1 font-semibold">{{ $errors->updatePassword->first('password') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                        Confirm Password <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                                           class="w-full bg-slate-50/50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] transition">
                                    @if($errors->updatePassword->has('password_confirmation'))
                                        <p class="text-rose-600 text-[11px] mt-1 font-semibold">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between flex-wrap gap-2">
                                <button type="submit" 
                                        class="bg-[#081a30] hover:bg-[#0c2544] text-[#d4af37] font-black px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shadow-sm cursor-pointer inline-flex items-center gap-2">
                                    <i class="fa-solid fa-key"></i> UPDATE PASSWORD
                                </button>
                                <span class="text-[11px] text-slate-400">
                                    Minimum 8 characters with letters & numbers.
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

        <!-- ==============================================
             MODAL 1: ORDER CANCELLATION MODAL
             ============================================== -->
        <div x-show="cancellingOrder !== null" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             @click.self="cancellingOrder = null">
            <div class="bg-white rounded-2xl max-w-lg w-full p-5 sm:p-6 shadow-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black uppercase tracking-wider text-rose-700 flex items-center gap-2">
                        <i class="fa-solid fa-ban"></i> Cancel Order
                    </h3>
                    <button type="button" @click="cancellingOrder = null" class="text-slate-400 hover:text-slate-700 p-1">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <template x-if="cancellingOrder">
                    <form :action="'{{ url('/orders') }}/' + cancellingOrder.id + '/cancel'" method="POST" class="space-y-4 text-xs">
                        @csrf

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="flex justify-between items-center text-slate-800 font-bold">
                                <span>Order Number:</span>
                                <span class="font-mono text-xs text-[#081a30]" x-text="'#' + (cancellingOrder.order_number || cancellingOrder.id)"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-600 mt-1">
                                <span>Total Amount:</span>
                                <span class="font-bold text-slate-900" x-text="'₹' + Number(cancellingOrder.total_amount).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-600 mt-1">
                                <span>Payment Method:</span>
                                <span class="font-bold uppercase" x-text="cancellingOrder.payment_method === 'cod' ? 'Cash on Delivery (COD)' : cancellingOrder.payment_method"></span>
                            </div>
                        </div>

                        <!-- Prepaid Notice -->
                        <div x-show="cancellingOrder.payment_method === 'razorpay' && cancellingOrder.payment_status === 'completed'" 
                             class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-[11px] leading-relaxed">
                            <i class="fa-solid fa-shield-halved text-emerald-600 mr-1"></i>
                            <strong>Prepaid Refund Guarantee:</strong> Since you paid online, your refund of <strong x-text="'₹' + Number(cancellingOrder.total_amount).toFixed(2)"></strong> will be automatically initiated to your original bank/UPI account via Razorpay.
                        </div>

                        <!-- Cancellation Reason -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                Reason for Cancellation <span class="text-rose-500">*</span>
                            </label>
                            <select name="cancellation_reason" x-model="cancellationReason" required
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]">
                                <option value="Ordered by mistake">Ordered by mistake</option>
                                <option value="Found better price or discount elsewhere">Found better price or discount elsewhere</option>
                                <option value="Expected delivery time is too long">Expected delivery time is too long</option>
                                <option value="Incorrect shipping address selected">Incorrect shipping address selected</option>
                                <option value="Change of mind / No longer required">Change of mind / No longer required</option>
                                <option value="Other reasons">Other reasons</option>
                            </select>
                        </div>

                        <!-- Optional Comments -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                Additional Feedback <span class="text-slate-400 font-normal">(optional)</span>
                            </label>
                            <textarea name="cancellation_comments" x-model="cancellationComments" rows="2" placeholder="Help us improve our service..."
                                      class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]"></textarea>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2.5">
                            <button type="button" @click="cancellingOrder = null" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-3.5 py-2">
                                Keep Order
                            </button>
                            <button type="submit" 
                                    class="bg-rose-600 hover:bg-rose-700 text-white font-extrabold px-5 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-sm cursor-pointer flex items-center gap-1.5">
                                <i class="fa-solid fa-check"></i> Confirm Cancellation
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- ==============================================
             MODAL 2: PRODUCT RETURN & EXCHANGE MODAL
             ============================================== -->
        <div x-show="returningOrder !== null" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             @click.self="returningOrder = null">
            <div class="bg-white rounded-2xl max-w-lg w-full p-5 sm:p-6 shadow-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#081a30] flex items-center gap-2">
                        <i class="fa-solid fa-arrow-rotate-left text-[#d4af37]"></i> Request Return or Exchange
                    </h3>
                    <button type="button" @click="returningOrder = null" class="text-slate-400 hover:text-slate-700 p-1">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <template x-if="returningOrder">
                    <form :action="'{{ url('/orders') }}/' + returningOrder.id + '/return'" method="POST" class="space-y-4 text-xs">
                        @csrf

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="flex justify-between items-center text-slate-800 font-bold">
                                <span>Order Number:</span>
                                <span class="font-mono text-xs text-[#081a30]" x-text="'#' + (returningOrder.order_number || returningOrder.id)"></span>
                            </div>
                            <div class="flex justify-between items-center text-slate-600 mt-1">
                                <span>Total Paid:</span>
                                <span class="font-bold text-slate-900" x-text="'₹' + Number(returningOrder.total_amount).toFixed(2)"></span>
                            </div>
                        </div>

                        <!-- Return Type (Radio) -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                                Select Request Type <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="border rounded-xl p-3 flex items-center gap-2.5 cursor-pointer transition"
                                       :class="returnType === 'return' ? 'border-[#081a30] bg-sky-50/50 font-bold' : 'border-slate-200 bg-white'">
                                    <input type="radio" name="return_type" value="return" x-model="returnType" class="text-[#081a30] focus:ring-[#081a30]">
                                    <div>
                                        <div class="text-xs text-slate-900 font-bold">Return for Refund</div>
                                        <div class="text-[10px] text-slate-500">Pick up &amp; credit back</div>
                                    </div>
                                </label>

                                <label class="border rounded-xl p-3 flex items-center gap-2.5 cursor-pointer transition"
                                       :class="returnType === 'replace' ? 'border-[#081a30] bg-sky-50/50 font-bold' : 'border-slate-200 bg-white'">
                                    <input type="radio" name="return_type" value="replace" x-model="returnType" class="text-[#081a30] focus:ring-[#081a30]">
                                    <div>
                                        <div class="text-xs text-slate-900 font-bold">Exchange Product</div>
                                        <div class="text-[10px] text-slate-500">Free replacement delivery</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Return Reason -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                Reason for Return / Exchange <span class="text-rose-500">*</span>
                            </label>
                            <select name="return_reason" x-model="returnReason" required
                                    class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]">
                                <option value="Damaged or defective packaging upon delivery">Damaged or defective packaging upon delivery</option>
                                <option value="Wrong product or formulation received">Wrong product or formulation received</option>
                                <option value="Product seal was broken">Product seal was broken</option>
                                <option value="Expired or near-expiry product">Expired or near-expiry product</option>
                                <option value="Not satisfied with product quality">Not satisfied with product quality</option>
                                <option value="Other medical or personal reasons">Other medical or personal reasons</option>
                            </select>
                        </div>

                        <!-- Detailed Description -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                Describe the Issue <span class="text-slate-400 font-normal">(optional)</span>
                            </label>
                            <textarea name="return_comments" x-model="returnComments" rows="2" placeholder="Please provide additional details regarding the condition of the items..."
                                      class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]"></textarea>
                        </div>

                        <!-- Refund Account Details (for COD returns) -->
                        <div x-show="returnType === 'return'">
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                Refund UPI ID or Bank Details <span class="text-slate-400 font-normal">(for COD refund credit)</span>
                            </label>
                            <input type="text" name="refund_account_details" x-model="refundAccountDetails" placeholder="e.g. UPI ID: username@upi or A/C No + IFSC"
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]">
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2.5">
                            <button type="button" @click="returningOrder = null" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-3.5 py-2">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-[#081a30] hover:bg-[#0c2544] text-[#d4af37] font-black px-5 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-sm cursor-pointer flex items-center gap-1.5">
                                <i class="fa-solid fa-paper-plane"></i> Submit Request
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- ==============================================
             MODAL 3: EDIT ADDRESS MODAL
             ============================================== -->
        <div x-show="editingAddress !== null" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             @click.self="editingAddress = null">
            <div class="bg-white rounded-2xl max-w-lg w-full p-5 sm:p-6 shadow-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black uppercase tracking-wider text-[#081a30] flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-[#d4af37]"></i> Edit Delivery Address
                    </h3>
                    <button type="button" @click="editingAddress = null" class="text-slate-400 hover:text-slate-700 p-1">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form :action="'{{ url('/addresses') }}/' + (editingAddress ? editingAddress.id : '')" method="POST" class="space-y-3 text-xs">
                    @csrf
                    @method('put')

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                            Street Address / Building <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="address" :value="editingAddress ? editingAddress.address : ''" required
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                            Apartment, Suite, Unit <span class="text-slate-400 font-normal">(optional)</span>
                        </label>
                        <input type="text" name="address2" :value="editingAddress ? editingAddress.address2 : ''"
                               class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">City *</label>
                            <input type="text" name="city" :value="editingAddress ? editingAddress.city : ''" required
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]">
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">State *</label>
                            <input type="text" name="state" :value="editingAddress ? editingAddress.state : ''" required
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">Pincode *</label>
                            <input type="text" name="zip" :value="editingAddress ? editingAddress.zip : ''" required
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]">
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1">Phone *</label>
                            <input type="tel" name="phone" :value="editingAddress ? editingAddress.phone : ''" required
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30]">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_default" id="edit_is_default" value="1" 
                               :checked="editingAddress && editingAddress.is_default"
                               class="rounded border-slate-300 text-[#081a30] focus:ring-[#081a30]">
                        <label for="edit_is_default" class="text-xs text-slate-700 font-bold select-none cursor-pointer">
                            Make this my default delivery address
                        </label>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button type="button" @click="editingAddress = null" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-3.5 py-2">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-[#081a30] hover:bg-[#0c2544] text-[#d4af37] font-black px-5 py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-sm cursor-pointer">
                            Update Address
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
