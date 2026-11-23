@extends('layouts.admin')

@section('header_title', 'Order Details')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <h3 class="font-serif font-bold text-slate-900 text-lg">Order #{{ $order->order_number }}</h3>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase tracking-wider
                    @if($order->status === 'completed') bg-emerald-100 text-emerald-800
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                    @elseif($order->status === 'cancelled' || $order->status === 'failed') bg-rose-100 text-rose-800
                    @else bg-amber-100 text-amber-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase tracking-wider
                    @if($order->payment_status === 'completed') bg-emerald-50 text-emerald-700 border border-emerald-200
                    @elseif($order->payment_status === 'failed') bg-rose-50 text-rose-700 border border-rose-200
                    @else bg-amber-50 text-amber-700 border border-amber-200
                    @endif">
                    Payment: {{ ucfirst($order->payment_status) }}
                </span>
            </div>
            <span class="text-xs text-slate-400 mt-1 block">Placed on: {{ $order->created_at->format('M d, Y - g:i A') }} ({{ $order->created_at->diffForHumans() }})</span>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3.5 py-2 rounded-xl transition">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Back to Orders</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Columns: Items & Customer Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Ordered Items Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <h4 class="font-bold text-slate-900 text-base mb-4 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span>Order Items ({{ $order->items->count() }})</span>
                    <span class="text-xs font-normal text-slate-400">Total Units: {{ $order->items->sum('quantity') }}</span>
                </h4>
                <div class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0">
                            <div class="flex items-center space-x-4">
                                @php
                                    $product = $item->product;
                                    $image = $product ? $product->primary_image_url : asset('images/dhuria_logo.png');
                                @endphp
                                <img src="{{ $image }}" alt="{{ $item->product_name }}" class="h-14 w-14 rounded-xl object-contain bg-slate-50 border border-slate-100 p-1 flex-shrink-0">
                                <div>
                                    <span class="block font-bold text-slate-900 text-sm">{{ $item->product_name }}</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-slate-500 font-medium">&#8377;{{ number_format($item->unit_price, 2) }} &times; {{ $item->quantity }}</span>
                                        @if($product && $product->sku)
                                            <span class="text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-mono">SKU: {{ $product->sku }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="font-extrabold text-slate-900 text-sm">&#8377;{{ number_format($item->total_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
                    <div class="flex justify-between items-center text-xs text-slate-500">
                        <span>Items Subtotal</span>
                        <span class="font-bold text-slate-800">&#8377;{{ number_format($order->items->sum('total_price'), 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs text-slate-500">
                        <span>Shipping Fee</span>
                        <span class="font-bold text-emerald-600">FREE</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                        <span class="text-sm font-extrabold text-slate-900">Grand Total</span>
                        <span class="text-xl font-black text-amber-600">&#8377;{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Cancellation Details Alert (if cancelled) -->
            @if($order->status === 'cancelled' || $order->cancellation_reason)
                <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                        <div class="flex-1 space-y-1">
                            <h4 class="font-extrabold text-rose-900 text-sm">Order Cancelled</h4>
                            <p class="text-xs text-rose-800 font-semibold">
                                Reason: <span class="font-normal text-rose-950">{{ $order->cancellation_reason ?: 'Cancelled by customer' }}</span>
                            </p>
                            @if($order->cancelled_at)
                                <p class="text-[11px] text-rose-600 font-medium">
                                    Cancelled on: {{ \Carbon\Carbon::parse($order->cancelled_at)->format('d M Y, h:i A') }}
                                </p>
                            @endif
                            @if($order->refund_id)
                                <div class="mt-2 pt-2 border-t border-rose-200/80 text-xs text-emerald-800 font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                    <span>Razorpay Refund ID: <strong class="font-mono">{{ $order->refund_id }}</strong> (&#8377;{{ number_format($order->refund_amount ?: $order->total_amount, 2) }})</span>
                                </div>
                            @elseif($order->refund_account_details)
                                <p class="text-[11px] text-slate-700 font-mono mt-1 bg-white/80 p-2 rounded border border-rose-100">
                                    Customer Refund Account: {{ $order->refund_account_details }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Return / Replacement Request Alert (if requested) -->
            @if($order->return_status || $order->return_reason)
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2 pb-2 border-b border-amber-200/80">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-arrow-rotate-left text-amber-600"></i>
                            <h4 class="font-extrabold text-amber-900 text-sm">Customer Return / Exchange Request</h4>
                        </div>
                        <span class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full
                            @if($order->return_status === 'approved') bg-emerald-100 text-emerald-800 border border-emerald-300
                            @elseif($order->return_status === 'rejected') bg-rose-100 text-rose-800 border border-rose-300
                            @else bg-amber-100 text-amber-900 border border-amber-300 @endif">
                            Return {{ ucfirst($order->return_status ?? 'Pending') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Request Type</span>
                            <span class="font-bold text-slate-900 uppercase">{{ $order->return_type ?: 'Return' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400">Return Reason</span>
                            <span class="font-bold text-slate-900">{{ $order->return_reason }}</span>
                        </div>
                        @if($order->return_comments)
                            <div class="sm:col-span-2">
                                <span class="block text-[10px] uppercase font-bold text-slate-400">Customer Comments</span>
                                <p class="text-slate-800 mt-0.5 bg-white p-2.5 rounded-xl border border-amber-100 leading-relaxed">{{ $order->return_comments }}</p>
                            </div>
                        @endif
                        @if($order->refund_account_details)
                            <div class="sm:col-span-2">
                                <span class="block text-[10px] uppercase font-bold text-slate-400">Customer Refund Details (UPI / Bank)</span>
                                <p class="text-slate-900 font-mono font-bold mt-0.5 bg-white p-2 rounded-lg border border-amber-100">{{ $order->refund_account_details }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Shipping & Customer Information Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <h4 class="font-bold text-slate-900 text-base mb-4 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span>Customer & Delivery Details</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $order->delivery_type === 'self_pickup' ? 'Warehouse Pickup' : 'Standard Delivery' }}
                    </span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Customer Name</span>
                        <span class="block font-bold text-slate-900 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-user text-slate-400 text-xs"></i>
                            {{ $order->shipping_name }}
                            @if(!$order->user_id)
                                <span class="text-[9px] bg-amber-100 text-amber-800 px-1.5 py-0.2 rounded font-extrabold">GUEST</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Contact Phone</span>
                        <a href="tel:{{ $order->shipping_phone }}" class="block font-bold text-slate-900 hover:text-amber-600 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-phone text-slate-400 text-xs"></i>
                            {{ $order->shipping_phone }}
                        </a>
                    </div>
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Email Address</span>
                        <a href="mailto:{{ $order->shipping_email }}" class="block font-bold text-slate-900 hover:text-amber-600 mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-envelope text-slate-400 text-xs"></i>
                            {{ $order->shipping_email }}
                        </a>
                    </div>
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Shipping Address</span>
                        <span class="block font-medium text-slate-800 mt-1 leading-relaxed">
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_zip }}
                        </span>
                    </div>
                </div>

                @if($order->notes)
                    <div class="mt-5 p-3.5 bg-amber-50/60 border border-amber-200/80 rounded-xl text-xs text-amber-900">
                        <span class="font-extrabold block mb-0.5"><i class="fa-solid fa-note-sticky mr-1"></i> Customer Order Notes:</span>
                        {{ $order->notes }}
                    </div>
                @endif
            </div>

            <!-- Shiprocket Logistics Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-lg bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-truck-ramp-box"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-base">Shiprocket Courier Dispatch</h4>
                            <span class="text-[11px] text-slate-400">Automated AWB generation & live parcel tracking</span>
                        </div>
                    </div>
                    @if($order->shiprocket_order_id)
                        <span class="bg-emerald-50 text-emerald-700 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border border-emerald-200">
                            Dispatched / Active
                        </span>
                    @else
                        <span class="bg-slate-100 text-slate-600 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full">
                            Not Yet Dispatched
                        </span>
                    @endif
                </div>

                @if($order->shiprocket_order_id)
                    <!-- Active Shiprocket Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs bg-purple-50/40 border border-purple-100 p-4 rounded-xl">
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-purple-700">Shiprocket Order ID</span>
                            <span class="font-mono font-bold text-slate-900">{{ $order->shiprocket_order_id }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-purple-700">Shipment ID</span>
                            <span class="font-mono font-bold text-slate-900">{{ $order->shiprocket_shipment_id ?: 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-purple-700">AWB Code</span>
                            <span class="font-mono font-black text-purple-900 select-all">{{ $order->shiprocket_awb_code ?: 'Pending AWB' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-purple-700">Shipment Status</span>
                            <span class="font-bold text-slate-900">{{ ucfirst($order->shiprocket_status ?: 'In Transit') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 pt-1">
                        <a href="{{ route('admin.orders.shiprocket.track', $order->id) }}" class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-xs">
                            <i class="fa-solid fa-location-crosshairs"></i>
                            <span>Track Live via Shiprocket API</span>
                        </a>
                        @if($order->shiprocket_awb_code)
                            <a href="https://shiprocket.co/tracking/{{ $order->shiprocket_awb_code }}" target="_blank" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold px-4 py-2.5 rounded-xl transition">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <span>Public Tracking Page</span>
                            </a>
                        @endif
                    </div>
                @else
                    <!-- Dispatch Trigger Button -->
                    <div class="p-4 bg-slate-50 border border-slate-200/60 rounded-xl space-y-3">
                        <p class="text-xs text-slate-600">Click below to send this order to Shiprocket, generate courier shipping labels, and assign an automatic AWB tracking code.</p>
                        <form method="POST" action="{{ route('admin.orders.shiprocket.create', $order->id) }}" onsubmit="return confirm('Send order #{{ $order->order_number }} to Shiprocket now?')">
                            @csrf
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs px-5 py-2.5 rounded-xl transition shadow-md shadow-purple-600/20 flex items-center space-x-2 cursor-pointer">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Dispatch to Shiprocket (Generate AWB)</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right 1 Column: Manage Status & Razorpay Payment -->
        <div class="space-y-6">
            
            <!-- Payment Information Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <h4 class="font-bold text-slate-900 text-base pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span>Payment Information</span>
                    <span class="text-xs font-black uppercase px-2 py-0.5 rounded {{ $order->payment_method === 'razorpay' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                        {{ strtoupper($order->payment_method) }}
                    </span>
                </h4>
                
                @if($order->payment_method === 'razorpay')
                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Razorpay Payment ID</span>
                            <span class="block font-mono font-bold text-slate-900 mt-0.5 select-all bg-slate-50 border border-slate-100 p-2 rounded-lg">
                                {{ $order->razorpay_payment_id ?: 'Pending / Unpaid' }}
                            </span>
                        </div>

                        <div>
                            <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Razorpay Order ID</span>
                            <span class="block font-mono font-semibold text-slate-700 mt-0.5 select-all bg-slate-50 border border-slate-100 p-2 rounded-lg">
                                {{ $order->razorpay_order_id ?: 'N/A' }}
                            </span>
                        </div>

                        @if($order->razorpay_signature)
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-emerald-600 tracking-wider">
                                    <i class="fa-solid fa-circle-check"></i> Server Verified Signature
                                </span>
                                <span class="block font-mono text-[10px] text-slate-500 mt-0.5 truncate bg-slate-50 p-1.5 rounded">
                                    {{ $order->razorpay_signature }}
                                </span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-3.5 bg-amber-50/70 border border-amber-200/80 rounded-xl text-xs space-y-1.5 text-amber-900">
                        <div class="font-extrabold flex items-center gap-1.5">
                            <i class="fa-solid fa-money-bill-wave text-amber-600"></i> Cash On Delivery (COD)
                        </div>
                        <p class="text-slate-600">Collect <strong class="text-slate-950 font-black">&#8377;{{ number_format($order->total_amount, 2) }}</strong> from customer upon delivery.</p>
                    </div>
                @endif
            </div>

            <!-- Update Order Status Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <h4 class="font-bold text-slate-900 text-base mb-4 pb-3 border-b border-slate-100">Update Order Status</h4>
                
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Order Status -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Fulfillment Status</label>
                        <select name="status" class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                            <option value="pending" @selected($order->status === 'pending')>Pending</option>
                            <option value="processing" @selected($order->status === 'processing')>Processing</option>
                            <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                            <option value="completed" @selected($order->status === 'completed')>Completed</option>
                            <option value="returned" @selected($order->status === 'returned')>Returned</option>
                            <option value="return_rejected" @selected($order->status === 'return_rejected')>Return Rejected</option>
                            <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                            <option value="failed" @selected($order->status === 'failed')>Failed</option>
                        </select>
                    </div>

                    <!-- Return Status (if return requested or active) -->
                    @if($order->return_status || $order->return_reason)
                        <div class="space-y-1.5 p-3 bg-amber-50 rounded-xl border border-amber-200">
                            <label class="text-xs font-bold text-amber-900 uppercase tracking-wider block">Return Approval</label>
                            <select name="return_status" class="w-full border border-amber-300 focus:ring-1 focus:ring-amber-500 rounded-xl text-sm px-3 py-2 bg-white">
                                <option value="pending" @selected($order->return_status === 'pending')>Under Review (Pending)</option>
                                <option value="approved" @selected($order->return_status === 'approved')>Approve Return &amp; Refund</option>
                                <option value="rejected" @selected($order->return_status === 'rejected')>Reject Return</option>
                            </select>
                            <p class="text-[10px] text-amber-700 mt-1">Approving will automatically mark status as 'Returned' and trigger gateway refund if prepaid.</p>
                        </div>
                    @endif

                    <!-- Payment Status -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wider block">Payment Status</label>
                        <select name="payment_status" class="w-full border border-slate-200 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 rounded-xl text-sm px-4 py-2.5 bg-slate-50/50">
                            <option value="pending" @selected($order->payment_status === 'pending')>Pending</option>
                            <option value="completed" @selected($order->payment_status === 'completed')>Paid (Completed)</option>
                            <option value="refund_pending" @selected($order->payment_status === 'refund_pending')>Refund Pending</option>
                            <option value="refunded" @selected($order->payment_status === 'refunded')>Refunded</option>
                            <option value="cancelled" @selected($order->payment_status === 'cancelled')>Cancelled (COD)</option>
                            <option value="failed" @selected($order->payment_status === 'failed')>Failed</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-[#d4af37] hover:bg-[#c59b27] text-slate-950 font-extrabold text-sm py-3 rounded-xl transition cursor-pointer shadow-md shadow-[#d4af37]/25">
                        Update Status
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
