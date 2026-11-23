@extends('layouts.frontend')

@section('title', 'Join Dhuria - Create Account')

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
            <div class="my-8 md:my-10">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-[#d4af37]/30 text-[#d4af37] text-[10px] font-extrabold uppercase tracking-widest mb-4">
                    <i class="bi bi-stars"></i> BEGIN YOUR JOURNEY
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-white leading-tight mb-4 tracking-tight">
                    Authentic Ayurveda for Modern Living.
                </h2>
                <p class="text-xs md:text-sm text-slate-300 leading-relaxed font-normal">
                    Join thousands of wellness seekers across India experiencing sustainable sugar balance, metabolic vitality, and holistic well-being.
                </p>
            </div>

            <!-- Trust Bullet Points -->
            <div class="space-y-2.5 pt-6 border-t border-white/10">
                <div class="flex items-center gap-2.5 text-xs text-slate-300">
                    <i class="bi bi-check-circle-fill text-[#d4af37]"></i>
                    <span>Zero Synthetic Fillers or Hidden Additives</span>
                </div>
                <div class="flex items-center gap-2.5 text-xs text-slate-300">
                    <i class="bi bi-truck text-[#d4af37]"></i>
                    <span>Pan-India Fast &amp; Secure Home Delivery</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Registration Form -->
        <div class="w-full md:w-[58%] bg-white p-8 md:p-10 flex flex-col justify-center">
            <!-- Top Tag Pill -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-wider mb-2.5 w-fit">
                <i class="bi bi-person-plus"></i> CREATE ACCOUNT
            </div>

            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight mb-1">
                Join Dhuria
            </h1>
            <p class="text-xs text-slate-500 mb-5">
                Create an account to track orders and save prescriptions.
            </p>

            <!-- Alerts -->
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
            <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                @csrf

                <!-- Full Name Input -->
                <div>
                    <label for="name" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                        FULL NAME
                    </label>
                    <div class="relative">
                        <i class="bi bi-person text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 text-sm"></i>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-[#081a30] focus:ring-2 focus:ring-[#081a30]/10 transition outline-none font-medium"
                               placeholder="e.g. Rahul Sharma">
                    </div>
                </div>

                <!-- Email Address Input -->
                <div>
                    <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                        EMAIL ADDRESS
                    </label>
                    <div class="relative">
                        <i class="bi bi-envelope text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 text-sm"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-[#081a30] focus:ring-2 focus:ring-[#081a30]/10 transition outline-none font-medium"
                               placeholder="e.g. rahul@example.com">
                    </div>
                </div>

                <!-- Mobile Number Input with +91 Prefix -->
                <div>
                    <label for="phone" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                        MOBILE NUMBER
                    </label>
                    <div class="flex rounded-xl overflow-hidden border border-slate-200 bg-slate-50 focus-within:bg-white focus-within:border-[#081a30] focus-within:ring-2 focus-within:ring-[#081a30]/10 transition">
                        <div class="flex items-center gap-1.5 px-3 bg-slate-100/80 border-r border-slate-200 text-slate-700 text-xs font-bold select-none">
                            <i class="bi bi-telephone text-slate-400 text-xs"></i>
                            <span>+91</span>
                        </div>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required pattern="[0-9]{10}" maxlength="10"
                               class="w-full px-3 py-2.5 bg-transparent text-xs sm:text-sm text-slate-900 placeholder-slate-400 outline-none font-medium"
                               placeholder="10-digit number">
                    </div>
                </div>

                <!-- 2-Column: Password & Confirm Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-data="{ showPass: false, showConfirm: false }">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                            PASSWORD
                        </label>
                        <div class="relative">
                            <i class="bi bi-lock text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-sm"></i>
                            <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password"
                                   class="w-full pl-8 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:border-[#081a30] focus:ring-2 focus:ring-[#081a30]/10 transition outline-none font-medium"
                                   placeholder="At least 8 chars">
                            <button type="button" @click="showPass = !showPass" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer text-xs" title="Toggle visibility">
                                <i class="bi" :class="showPass ? 'bi-eye-slash' : 'bi-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">
                            CONFIRM PASSWORD
                        </label>
                        <div class="relative">
                            <i class="bi bi-lock text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-sm"></i>
                            <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                                   class="w-full pl-8 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:border-[#081a30] focus:ring-2 focus:ring-[#081a30]/10 transition outline-none font-medium"
                                   placeholder="Repeat password">
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer text-xs" title="Toggle visibility">
                                <i class="bi" :class="showConfirm ? 'bi-eye-slash' : 'bi-eye'"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Wellness Tips Opt-in Checkbox -->
                <div class="pt-1">
                    <label class="flex items-start gap-2.5 cursor-pointer text-xs text-slate-600 select-none">
                        <input type="checkbox" name="newsletter" value="1" checked class="w-4 h-4 mt-0.5 rounded text-[#081a30] border-slate-300 focus:ring-0 cursor-pointer">
                        <span class="text-[11px] leading-snug text-slate-500">Send me Ayurvedic wellness tips and special offers. You can unsubscribe anytime.</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#081a30] hover:bg-[#0d2544] text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-xs uppercase tracking-wider mt-3 cursor-pointer">
                    CREATE ACCOUNT <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <!-- Bottom Navigation -->
            <div class="mt-5 text-center text-xs text-slate-500">
                Already registered? 
                <a href="{{ route('login') }}" class="font-bold text-slate-900 hover:underline ml-1">
                    Sign in here
                </a>
            </div>

            <!-- Privacy Note -->
            <div class="mt-3 flex items-center justify-center gap-1.5 text-[11px] text-slate-400">
                <i class="bi bi-shield-check text-emerald-600"></i>
                <span>Your information is strictly protected by privacy laws</span>
            </div>
        </div>
    </div>
</div>
@endsection
