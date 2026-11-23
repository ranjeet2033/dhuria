@extends('layouts.frontend')

@section('title', 'My Account - Dhuria')

@section('content')
<div class="bg-slate-50 py-8 md:py-12 min-h-screen" x-data="{ activeTab: 'profile' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Top Header / Kicker matching Image 10 -->
        <div class="mb-8">
            <span class="text-[11px] font-extrabold tracking-widest uppercase text-slate-400 block mb-1">YOUR SPACE</span>
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#081a30] tracking-tight">My Account</h1>
            <p class="text-xs text-slate-500 mt-1">Manage your profile, orders, addresses and account settings.</p>
        </div>

        <!-- Flash Status Alerts -->
        @if (session('status') === 'profile-updated')
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-xs font-semibold shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>Your profile details have been successfully updated.</span>
            </div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-xs font-semibold shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>Your password has been changed successfully.</span>
            </div>
        @endif
        @if (isset($errors) && $errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl px-4 py-3 text-xs">
                <div class="font-bold mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> Action could not be completed:
                </div>
                <ul class="list-disc list-inside space-y-0.5 ml-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Sidebar Card (Soft Sky-Blue Card matching Image 10) -->
            <div class="lg:col-span-4">
                <div class="bg-[#eaf4fc] border border-sky-100 rounded-2xl p-6 shadow-xs">
                    
                    <!-- Avatar circle with User Initial -->
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 rounded-full bg-[#081a30] text-white flex items-center justify-center font-extrabold text-2xl mx-auto shadow-md mb-3">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h2 class="text-base font-extrabold text-slate-900 leading-snug">{{ Auth::user()->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5 break-all">{{ Auth::user()->email }}</p>
                        @if(Auth::user()->is_admin)
                            <span class="inline-block mt-2 bg-amber-500 text-slate-950 font-black text-[9px] uppercase tracking-wider px-2 py-0.5 rounded-full">Administrator</span>
                        @endif
                    </div>

                    <!-- Navigation Items -->
                    <nav class="space-y-1.5 text-xs font-semibold">
                        <button type="button" @click="activeTab = 'profile'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left cursor-pointer"
                                :class="activeTab === 'profile' ? 'bg-[#081a30] text-white font-bold shadow-sm' : 'text-slate-700 hover:bg-white/60'">
                            <i class="fa-solid fa-user text-xs w-4 text-center"></i>
                            <span>Profile</span>
                        </button>

                        <button type="button" @click="activeTab = 'orders'"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition text-left cursor-pointer"
                                :class="activeTab === 'orders' ? 'bg-[#081a30] text-white font-bold shadow-sm' : 'text-slate-700 hover:bg-white/60'">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-bag-shopping text-xs w-4 text-center"></i>
                                <span>Orders</span>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold"
                                  :class="activeTab === 'orders' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                                {{ count($orders) }}
                            </span>
                        </button>

                        <button type="button" @click="activeTab = 'addresses'"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition text-left cursor-pointer"
                                :class="activeTab === 'addresses' ? 'bg-[#081a30] text-white font-bold shadow-sm' : 'text-slate-700 hover:bg-white/60'">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-location-dot text-xs w-4 text-center"></i>
                                <span>Addresses</span>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold"
                                  :class="activeTab === 'addresses' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'">
                                {{ count($addresses) }}
                            </span>
                        </button>

                        <button type="button" @click="activeTab = 'settings'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left cursor-pointer"
                                :class="activeTab === 'settings' ? 'bg-[#081a30] text-white font-bold shadow-sm' : 'text-slate-700 hover:bg-white/60'">
                            <i class="fa-solid fa-lock text-xs w-4 text-center"></i>
                            <span>Settings</span>
                        </button>

                        <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-sky-200/60 mt-2">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-rose-600 hover:bg-rose-50 font-bold transition text-left cursor-pointer">
                                <i class="fa-solid fa-arrow-right-from-bracket text-xs w-4 text-center"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </nav>

                </div>
            </div>

            <!-- Right Main Area -->
            <div class="lg:col-span-8">
                
                <!-- TAB 1: Profile (Personal Details matching Image 10) -->
                <div x-show="activeTab === 'profile'" class="space-y-6">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs">
                        <div class="pb-4 mb-6 border-b border-slate-100">
                            <h2 class="text-xl font-bold text-slate-900">Personal Details</h2>
                            <p class="text-xs text-slate-500 mt-1">Update your personal information and contact details.</p>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                            @csrf
                            @method('patch')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                       class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] focus:ring-1 focus:ring-[#081a30] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                       class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] focus:ring-1 focus:ring-[#081a30] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="10-digit mobile number"
                                       class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] focus:ring-1 focus:ring-[#081a30] transition">
                            </div>

                            <div class="pt-2">
                                <button type="submit" 
                                        class="bg-[#081a30] hover:bg-[#0c2544] text-white font-bold px-6 py-3 rounded-xl text-xs uppercase tracking-wider transition shadow-sm cursor-pointer">
                                    SAVE CHANGES
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Quick Stats Overview Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 text-center shadow-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Orders</span>
                            <span class="text-xl font-black text-slate-900 mt-1 block">{{ count($orders) }}</span>
                        </div>
                        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 text-center shadow-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">In Progress</span>
                            <span class="text-xl font-black text-amber-600 mt-1 block">{{ $activeOrders->count() }}</span>
                        </div>
                        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 text-center shadow-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Delivered</span>
                            <span class="text-xl font-black text-emerald-600 mt-1 block">{{ $orders->where('status', 'delivered')->count() }}</span>
                        </div>
                        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 text-center shadow-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Spent</span>
                            <span class="text-xl font-black text-slate-900 mt-1 block">&#8377;{{ number_format($orders->where('payment_status', 'completed')->sum('total_amount'), 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: Orders -->
                <div x-show="activeTab === 'orders'" class="space-y-6" style="display: none;">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs">
                        <div class="pb-4 mb-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Your Orders</h2>
                                <p class="text-xs text-slate-500 mt-1">Review tracking status, order invoices, and details.</p>
                            </div>
                            <a href="{{ url('/shop') }}" class="text-xs text-[#081a30] font-bold hover:underline">
                                Browse Shop &rarr;
                            </a>
                        </div>

                        @if($orders->isEmpty())
                            <div class="text-center py-12">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-900">No Orders Placed Yet</h3>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-4">
                                    Explore authentic Dhuria Ayurvedic formulations for diabetes management, liver detox, and vitality.
                                </p>
                                <a href="{{ url('/shop') }}" class="inline-flex items-center gap-2 bg-[#d4af37] text-slate-950 font-bold px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider">
                                    Explore Dhuria Products
                                </a>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($orders as $ord)
                                    <div class="border border-slate-200 rounded-xl p-4 sm:p-5 hover:border-slate-300 transition bg-slate-50/50">
                                        <div class="flex items-center justify-between flex-wrap gap-2 pb-3 mb-3 border-b border-slate-200/80">
                                            <div>
                                                <span class="text-xs font-black text-slate-900">Order #{{ $ord->order_number ?? $ord->id }}</span>
                                                <span class="text-[11px] text-slate-400 ml-2">{{ $ord->created_at->format('d M Y, h:i A') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded
                                                    @if($ord->status === 'delivered') bg-emerald-100 text-emerald-800
                                                    @elseif($ord->status === 'cancelled') bg-rose-100 text-rose-800
                                                    @else bg-amber-100 text-amber-800 @endif">
                                                    {{ ucfirst($ord->status) }}
                                                </span>
                                                <span class="text-xs font-black text-slate-900">&#8377;{{ number_format($ord->total_amount, 2) }}</span>
                                            </div>
                                        </div>

                                        <!-- Items preview -->
                                        <div class="space-y-2">
                                            @foreach($ord->items as $item)
                                                <div class="flex items-center justify-between text-xs">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 p-1 flex items-center justify-center flex-shrink-0">
                                                            <img src="{{ $item->product ? $item->product->primary_image_url : asset('images/diabetes_pro_product.jpg') }}" alt="" class="max-w-full max-h-full object-contain">
                                                        </div>
                                                        <div>
                                                            <strong class="text-slate-800">{{ $item->product_name ?? ($item->product ? $item->product->name : 'Ayurvedic Product') }}</strong>
                                                            <div class="text-[11px] text-slate-400">Qty: {{ $item->quantity }} × &#8377;{{ number_format($item->price, 0) }}</div>
                                                        </div>
                                                    </div>
                                                    <span class="font-bold text-slate-900">&#8377;{{ number_format($item->price * $item->quantity, 0) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- TAB 3: Addresses -->
                <div x-show="activeTab === 'addresses'" class="space-y-6" style="display: none;">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs">
                        <div class="pb-4 mb-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Saved Addresses</h2>
                                <p class="text-xs text-slate-500 mt-1">Manage delivery locations for quick checkout.</p>
                            </div>
                            <a href="{{ url('/addresses') }}" class="text-xs text-[#081a30] font-bold hover:underline">
                                Manage Addresses Page &rarr;
                            </a>
                        </div>

                        @if($addresses->isEmpty())
                            <div class="text-center py-10">
                                <i class="fa-solid fa-location-dot text-3xl text-slate-300 mb-2"></i>
                                <p class="text-xs text-slate-500">No delivery address saved yet.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($addresses as $addr)
                                    <div class="border border-slate-200 rounded-xl p-4 relative bg-slate-50/50">
                                        @if($addr->is_default)
                                            <span class="absolute top-3 right-3 text-[9px] bg-emerald-600 text-white font-extrabold uppercase px-1.5 py-0.5 rounded">Default</span>
                                        @endif
                                        <p class="text-xs font-bold text-slate-900">{{ $addr->address }}@if($addr->address2), {{ $addr->address2 }}@endif</p>
                                        <p class="text-xs text-slate-600 mt-1">{{ $addr->city }}, {{ $addr->state }} - {{ $addr->zip }}</p>
                                        <p class="text-[11px] text-slate-400 mt-2"><i class="fa-solid fa-phone text-[10px]"></i> {{ $addr->phone }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- TAB 4: Settings (Change Password) -->
                <div x-show="activeTab === 'settings'" class="space-y-6" style="display: none;">
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs">
                        <div class="pb-4 mb-6 border-b border-slate-100">
                            <h2 class="text-xl font-bold text-slate-900">Account Security</h2>
                            <p class="text-xs text-slate-500 mt-1">Update your password to keep your account safe.</p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                            @csrf
                            @method('put')

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Current Password</label>
                                <input type="password" name="current_password" required
                                       class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] focus:ring-1 focus:ring-[#081a30] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">New Password</label>
                                <input type="password" name="password" required
                                       class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] focus:ring-1 focus:ring-[#081a30] transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full bg-white border border-slate-300 rounded-xl px-4 py-3 text-xs text-slate-900 font-medium focus:outline-none focus:border-[#081a30] focus:ring-1 focus:ring-[#081a30] transition">
                            </div>

                            <div class="pt-2">
                                <button type="submit" 
                                        class="bg-[#081a30] hover:bg-[#0c2544] text-white font-bold px-6 py-3 rounded-xl text-xs uppercase tracking-wider transition shadow-sm cursor-pointer">
                                    UPDATE PASSWORD
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
