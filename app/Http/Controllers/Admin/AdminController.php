<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSales = \Illuminate\Support\Facades\Cache::remember('admin_dash_sales', 60, function() {
            return Order::where('payment_status', 'completed')->sum('total_amount');
        });

        $totalOrders = \Illuminate\Support\Facades\Cache::remember('admin_dash_orders', 60, function() {
            return Order::count();
        });

        $pendingOrders = \Illuminate\Support\Facades\Cache::remember('admin_dash_pending_orders', 30, function() {
            return Order::where('status', 'pending')->count();
        });

        $completedOrders = \Illuminate\Support\Facades\Cache::remember('admin_dash_completed_orders', 60, function() {
            return Order::where('status', 'completed')->count();
        });

        $totalProducts = \Illuminate\Support\Facades\Cache::remember('admin_dash_products', 120, function() {
            return Product::count();
        });

        $totalUsers = \Illuminate\Support\Facades\Cache::remember('admin_dash_users', 120, function() {
            return User::where('is_admin', false)->count();
        });

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalProducts',
            'totalUsers',
            'recentOrders'
        ));
    }

    public function settings()
    {
        $settings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function() {
            return Setting::pluck('value', 'key')->all();
        });
        return view('admin.settings.edit', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        \Illuminate\Support\Facades\Cache::forget('site_settings');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
