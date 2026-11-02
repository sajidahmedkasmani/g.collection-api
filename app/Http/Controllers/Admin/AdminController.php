<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $canViewProducts = $user->can('products.view');
        $canViewOrders = $user->can('orders.view');
        $stats = [
            'products' => $canViewProducts ? Product::count() : 0,
            'orders' => $canViewOrders ? Order::count() : 0,
            'pending_orders' => $canViewOrders ? Order::where('status', 'pending')->count() : 0,
            'revenue' => $canViewOrders ? Order::where('status', 'completed')->sum('total') : 0,
        ];
        $recentOrders = $canViewOrders ? Order::latest()->take(5)->get() : collect();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}