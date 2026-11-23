@extends('layouts.admin')

@section('header_title', 'Store & Gateway Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between pb-2">
        <div>
            <h3 class="font-serif font-bold text-slate-900 text-xl tracking-tight">Configuration & Integrations</h3>
            <p class="text-xs text-slate-500 mt-1">Manage platform credentials, Razorpay gateway, Shiprocket logistics and store contact details.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf

        <!-- Shop Identity -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                <div class="h-8 w-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Brand & Contact Identity</h4>
                    <span class="text-[11px] text-slate-400">Used across order confirmations, invoices, and customer communications</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Site Name -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Store Brand Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Dhuria' }}" placeholder="Dhuria"
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                </div>

                <!-- Site Email -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Customer Support Email</label>
                    <input type="email" name="site_email" value="{{ $settings['site_email'] ?? 'support@dhuria.in' }}" placeholder="support@dhuria.in"
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                </div>

                <!-- Site Phone -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Helpline Phone Number</label>
                    <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '+91 9220251059' }}" placeholder="+91 9220251059"
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                </div>

                <!-- WhatsApp Number -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">WhatsApp Support Number</label>
                    <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '919220251059' }}" placeholder="919220251059"
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                </div>
            </div>

            <!-- Site Address -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Registered Address / Head Office</label>
                <textarea name="site_address" rows="2" placeholder="Dhuria Healthcare, New Delhi, India"
                          class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">{{ $settings['site_address'] ?? 'Dhuria Healthcare, Delhi, India' }}</textarea>
            </div>
        </div>

        <!-- Razorpay Payment Gateway Settings -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Razorpay Payment Gateway</h4>
                        <span class="text-[11px] text-slate-400">Accept UPI, Credit/Debit Cards, NetBanking, and Wallets on Checkout</span>
                    </div>
                </div>
                <span class="bg-blue-50 text-blue-700 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full border border-blue-200">
                    Live / Test
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Razorpay Key ID -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Razorpay Key ID</label>
                    <input type="text" name="razorpay_key" value="{{ $settings['razorpay_key'] ?? config('services.razorpay.key') }}" placeholder="rzp_test_... or rzp_live_..."
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50 font-mono">
                    <span class="text-[10px] text-slate-400">Find in your Razorpay Dashboard &rarr; Settings &rarr; API Keys</span>
                </div>

                <!-- Razorpay Key Secret -->
                <div class="space-y-1.5" x-data="{ showSecret: false }">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Razorpay Key Secret</label>
                    <div class="relative">
                        <input :type="showSecret ? 'text' : 'password'" name="razorpay_secret" value="{{ $settings['razorpay_secret'] ?? config('services.razorpay.secret') }}" placeholder="••••••••••••••••••••••••"
                               class="w-full border border-slate-200 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 rounded-xl text-sm pl-4 pr-10 py-2.5 bg-slate-50/50 font-mono">
                        <button type="button" @click="showSecret = !showSecret" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none cursor-pointer">
                            <i class="fa-solid text-sm" :class="showSecret ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <span class="text-[10px] text-slate-400">Keep secret confidential. Used to verify payment signatures securely.</span>
                </div>
            </div>

            <!-- Razorpay Webhook Secret -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Razorpay Webhook Secret (Optional)</label>
                <input type="text" name="razorpay_webhook_secret" value="{{ $settings['razorpay_webhook_secret'] ?? config('services.razorpay.webhook_secret') }}" placeholder="Enter secret configured in Razorpay Webhooks"
                       class="w-full border border-slate-200 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50 font-mono">
                <span class="text-[10px] text-slate-400">Webhook URL: <code class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded">{{ url('/checkout/razorpay-webhook') }}</code></span>
            </div>
        </div>

        <!-- Shiprocket Logistics Integration Settings -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-lg bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Shiprocket Shipping & Logistics</h4>
                        <span class="text-[11px] text-slate-400">Automated courier dispatch, AWB generation, and real-time live package tracking</span>
                    </div>
                </div>
                <span class="bg-purple-50 text-purple-700 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full border border-purple-200">
                    India Logistics
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Shiprocket Login Email -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Shiprocket Account Email</label>
                    <input type="email" name="shiprocket_email" value="{{ $settings['shiprocket_email'] ?? '' }}" placeholder="your-shiprocket-account@domain.com"
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-purple-500 focus:border-purple-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                    <span class="text-[10px] text-slate-400">Email used to log in at shiprocket.in</span>
                </div>

                <!-- Shiprocket Password -->
                <div class="space-y-1.5" x-data="{ showSecret: false }">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Shiprocket Account Password</label>
                    <div class="relative">
                        <input :type="showSecret ? 'text' : 'password'" name="shiprocket_password" value="{{ $settings['shiprocket_password'] ?? '' }}" placeholder="••••••••••••••••"
                               class="w-full border border-slate-200 focus:ring-1 focus:ring-purple-500 focus:border-purple-500 rounded-xl text-sm pl-4 pr-10 py-2.5 bg-slate-50/50">
                        <button type="button" @click="showSecret = !showSecret" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-purple-600 focus:outline-none cursor-pointer">
                            <i class="fa-solid text-sm" :class="showSecret ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <span class="text-[10px] text-slate-400">Used for secure external API JWT token generation</span>
                </div>
            </div>

            <!-- Shiprocket Pickup Location -->
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Registered Pickup Location Nickname</label>
                <input type="text" name="shiprocket_pickup_location" value="{{ $settings['shiprocket_pickup_location'] ?? 'Primary' }}" placeholder="Primary"
                       class="w-full border border-slate-200 focus:ring-1 focus:ring-purple-500 focus:border-purple-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                <span class="text-[10px] text-slate-400">Must exactly match the Pickup Location Name created in your Shiprocket Dashboard &rarr; Settings &rarr; Pickup Locations (default: "Primary")</span>
            </div>
        </div>

        <!-- Social Media Settings -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                <div class="h-8 w-8 rounded-lg bg-rose-500/10 text-rose-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-share-nodes"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">Social Media Channels</h4>
                    <span class="text-[11px] text-slate-400">Links rendered in footer and customer contact touchpoints</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Instagram -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block"><i class="fa-brands fa-instagram text-rose-500 mr-1"></i> Instagram</label>
                    <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/dhuria"
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                </div>

                <!-- Facebook -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block"><i class="fa-brands fa-facebook text-blue-600 mr-1"></i> Facebook</label>
                    <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/dhuria"
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                </div>

                <!-- YouTube -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block"><i class="fa-brands fa-youtube text-red-600 mr-1"></i> YouTube</label>
                    <input type="url" name="social_youtube" value="{{ $settings['social_youtube'] ?? '' }}" placeholder="https://youtube.com/@dhuria"
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                </div>

                <!-- Twitter / X -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block"><i class="fa-brands fa-x-twitter text-slate-900 mr-1"></i> Twitter / X</label>
                    <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://x.com/dhuria"
                           class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-2">
            <button type="submit" class="w-full sm:w-auto bg-[#d4af37] hover:bg-[#c59b27] text-slate-950 font-extrabold text-sm px-8 py-3.5 rounded-xl shadow-lg shadow-[#d4af37]/25 transition cursor-pointer flex items-center justify-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save All Settings</span>
            </button>
        </div>
    </form>

</div>
@endsection
