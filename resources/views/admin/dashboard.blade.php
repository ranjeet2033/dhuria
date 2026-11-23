@extends('layouts.admin')

@section('header_title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-[#0a111e] via-[#101b2d] to-[#16253d] rounded-3xl p-6 sm:p-8 text-white border border-slate-800 shadow-xl">
        <div class="relative z-10 max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-[#d4af37] text-xs font-bold uppercase tracking-wider mb-3">
                <i class="fa-solid fa-crown text-[10px]"></i>
                <span>Dhuria Ayurvedic Store Hub</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                Welcome back, {{ auth()->user()->name }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                Here is what is happening across your products, live orders, Shiprocket logistics, and customer engagements today.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <i class="fa-solid fa-mortar-pestle text-[220px] text-[#d4af37]"></i>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- Card 1: Total Sales -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Revenue</span>
                <div class="bg-amber-50 text-[#d4af37] h-9 w-9 rounded-xl flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="block text-2xl font-black text-slate-900 tracking-tight">&#8377;{{ number_format($totalSales) }}</span>
                <span class="text-[11px] text-emerald-600 font-bold mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Paid Orders
                </span>
            </div>
        </div>

        <!-- Card 2: Total Orders -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Orders</span>
                <div class="bg-blue-50 text-blue-600 h-9 w-9 rounded-xl flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="block text-2xl font-black text-slate-900 tracking-tight">{{ $totalOrders }}</span>
                <span class="text-[11px] text-slate-500 font-medium mt-1 block">Lifetime placed</span>
            </div>
        </div>

        <!-- Card 3: Pending Orders -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Pending Orders</span>
                <div class="bg-orange-50 text-orange-600 h-9 w-9 rounded-xl flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="block text-2xl font-black text-slate-900 tracking-tight">{{ $pendingOrders }}</span>
                <span class="text-[11px] text-orange-600 font-bold mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-bell text-[10px] animate-bounce"></i> Needs Action
                </span>
            </div>
        </div>

        <!-- Card 4: Completed Orders -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Completed</span>
                <div class="bg-emerald-50 text-emerald-600 h-9 w-9 rounded-xl flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="block text-2xl font-black text-slate-900 tracking-tight">{{ $completedOrders }}</span>
                <span class="text-[11px] text-emerald-600 font-medium mt-1 block">Successfully delivered</span>
            </div>
        </div>

        <!-- Card 5: Products -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Live Catalog</span>
                <div class="bg-purple-50 text-purple-600 h-9 w-9 rounded-xl flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-capsules"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="block text-2xl font-black text-slate-900 tracking-tight">{{ $totalProducts }}</span>
                <span class="text-[11px] text-purple-600 font-medium mt-1 block">Active formulations</span>
            </div>
        </div>

        <!-- Card 6: Customers -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Customers</span>
                <div class="bg-indigo-50 text-indigo-600 h-9 w-9 rounded-xl flex items-center justify-center text-sm font-bold shadow-2xs">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="block text-2xl font-black text-slate-900 tracking-tight">{{ $totalUsers }}</span>
                <span class="text-[11px] text-indigo-600 font-medium mt-1 block">Registered clients</span>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-serif font-bold text-slate-900 text-lg">Recent Orders</h3>
                <span class="text-xs text-slate-400">Latest customer purchases across COD and Razorpay</span>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-[#b89127] hover:text-[#997316] flex items-center gap-1.5 transition">
                <span>View All Orders</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-500 text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="px-6 py-3.5">Order No.</th>
                        <th class="px-6 py-3.5">Customer</th>
                        <th class="px-6 py-3.5">Order Status</th>
                        <th class="px-6 py-3.5">Payment</th>
                        <th class="px-6 py-3.5">Amount</th>
                        <th class="px-6 py-3.5">Placed On</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-bold text-slate-900 font-mono">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="block font-bold text-slate-900">{{ $order->shipping_name }}</span>
                                <span class="block text-[11px] text-slate-400">{{ $order->shipping_email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                        Completed
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-blue-100 text-blue-800">
                                        Processing
                                    </span>
                                @elseif($order->status === 'shipped')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-purple-100 text-purple-800">
                                        Shipped
                                    </span>
                                @elseif($order->status === 'cancelled' || $order->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-rose-100 text-rose-800">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-100 text-amber-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($order->payment_status === 'completed')
                                    <span class="inline-flex items-center gap-1 font-bold text-emerald-700 text-xs">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Paid ({{ strtoupper($order->payment_method) }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 font-bold text-amber-600 text-xs">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Pending ({{ strtoupper($order->payment_method) }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-black text-slate-900 text-sm">&#8377;{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-[11px] text-slate-400">{{ $order->created_at->format('M d, Y g:i A') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 text-xs font-extrabold bg-slate-100 hover:bg-[#d4af37] hover:text-slate-950 text-slate-700 px-3 py-1.5 rounded-lg transition duration-150">
                                    <span>Details</span>
                                    <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400 font-medium">No recent orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
