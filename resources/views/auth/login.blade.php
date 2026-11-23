@extends('layouts.frontend')

@section('title', 'Sign In - Dhuria Ayurveda')

@section('content')
<div class="min-h-[82vh] bg-slate-50/60 py-8 px-4 sm:px-6 lg:px-8 flex flex-col justify-center" style="font-family: 'Outfit', sans-serif;">
    <div class="max-w-4xl w-full mx-auto mb-3">
        <a href="{{ route('shop') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i class="bi bi-arrow-left"></i> Back to Shop
        </a>
    </div>

    <div class="max-w-4xl w-full mx-auto bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200/80 flex flex-col md:flex-row">
        <!-- Left Column: Dark Navy Brand Showcase -->
        <div class="w-full md:w-[42%] bg-[#081a30] text-white p-8 md:p-10 flex flex-col justify-between relative overflow-hidden" style="background: radial-gradient(circle at 15% 20%, rgba(212, 175, 55, 0.15) 0%, transparent 45%), radial-gradient(circle at 85% 85%, rgba(13, 37, 68, 0.9) 0%, transparent 55%), #081a30;">
            <!-- Brand Header -->
            <div>
                <a href="/" class="inline-flex items-center gap-3 text-decoration-none mb-1">
                    <img src="{{ asset('images/dhuria_logo_white.png') }}" alt="Dhuria" class="h-8 md:h-9 w-auto object-contain">
                </a>
                <div class="text-[9px] font-black uppercase tracking-[0.22em] text-[#d4af37] mt-1">
                    PURE AYURVEDA &bull; BETTER LIFE
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="my-8 md:my-12">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-[#d4af37]/30 text-[#d4af37] text-[10px] font-extrabold uppercase tracking-widest mb-4">
                    <i class="bi bi-stars"></i> CUSTOMER SIGN IN
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-white leading-tight mb-4 tracking-tight">
                    Welcome to Authentic Wellness.
                </h2>
                <p class="text-xs md:text-sm text-slate-300 leading-relaxed font-normal">
                    Sign in to manage your orders, track fast dispatch, access saved prescriptions, and explore personalized Ayurvedic formulations.
                </p>
            </div>

            <!-- Trust Bullet Points -->
            <div class="space-y-2.5 pt-6 border-t border-white/10">
                <div class="flex items-center gap-2.5 text-xs text-slate-300">
                    <i class="bi bi-check-circle-fill text-[#d4af37]"></i>
                    <span>100% Certified Classical Ayurvedic Herbs</span>
                </div>
                <div class="flex items-center gap-2.5 text-xs text-slate-300">
                    <i class="bi bi-shield-check text-[#d4af37]"></i>
                    <span>Encrypted &amp; Protected Sign-in Session</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Sign In Form -->
        <div class="w-full md:w-[58%] bg-white p-8 md:p-12 flex flex-col justify-center">
            <!-- Top Tag Pill -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-wider mb-2.5 w-fit">
                <i class="bi bi-box-arrow-in-right"></i> WELCOME BACK
            </div>

            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight mb-1">
                Sign In to Account
            </h1>
            <p class="text-xs text-slate-500 mb-6">
                Enter your registered credentials to access your store orders.
            </p>

            <!-- Alerts -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center gap-2">
                    <i class="bi bi-check-circle-fill text-emerald-600"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i class="bi bi-exclamation-circle-fill text-rose-600"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        EMAIL ADDRESS
                    </label>
                    <div class="relative">
                        <i class="bi bi-envelope text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 text-sm"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-[#081a30] focus:ring-2 focus:ring-[#081a30]/10 transition outline-none font-medium"
                               placeholder="e.g. yourname@example.com">
                    </div>
                </div>

                <!-- Password Input -->
                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700">
                            PASSWORD
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-amber-700 hover:text-amber-800 font-semibold hover:underline">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <i class="bi bi-lock text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 text-sm"></i>
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                               class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-[#081a30] focus:ring-2 focus:ring-[#081a30]/10 transition outline-none font-medium"
                               placeholder="Enter your password">
                        <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer text-sm" title="Toggle password visibility">
                            <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="flex items-center gap-2 cursor-pointer text-xs text-slate-600 select-none">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded text-[#081a30] border-slate-300 focus:ring-0 cursor-pointer">
                        <span>Remember me on this device</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#081a30] hover:bg-[#0d2544] text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-xs uppercase tracking-wider mt-3 cursor-pointer">
                    SIGN IN <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <!-- Bottom Navigation -->
            <div class="mt-6 text-center text-xs text-slate-500">
                New to Dhuria? 
                <a href="{{ route('register') }}" class="font-bold text-slate-900 hover:underline ml-1">
                    Create an account
                </a>
            </div>

            <!-- SSL Security Badge -->
            <div class="mt-4 flex items-center justify-center gap-1.5 text-[11px] text-slate-400">
                <i class="bi bi-shield-check text-emerald-600"></i>
                <span>256-bit SSL encrypted connection</span>
            </div>
        </div>
    </div>
</div>
@endsection
