<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'revenue' => Order::where('status', 'completed')->sum('total'),
        ];
        $recentOrders = Order::latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}