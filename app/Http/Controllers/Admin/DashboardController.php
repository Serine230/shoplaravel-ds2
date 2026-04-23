<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'    => User::count(),
            'total_products' => Product::count(),
            'total_orders'   => Order::count(),
            'total_revenue'  => Order::where('status', 'livree')->sum('total'),
            'pending_orders' => Order::where('status', 'en_attente')->count(),
            'low_stock'      => Product::where('stock', '<', 5)->count(),
        ];

        $recentOrders   = Order::with('user', 'items')->latest()->take(10)->get();
        $topProducts    = Product::withCount('orderItems')->orderByDesc('order_items_count')->take(5)->get();
        $monthlyRevenue = Order::where('status', 'livree')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'monthlyRevenue'));
    }
}
