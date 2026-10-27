<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty.');
        }
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('shop.checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:50',
            'payment_method' => 'required|in:cod,bank_transfer',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty.');
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = 0;
        foreach ($cart as $id => $item) {
            if (!isset($products[$id])) {
                return back()->with('error', 'One product is no longer available.');
            }
            $total += $products[$id]->price * $item['quantity'];
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => 'GC-' . strtoupper(Str::random(8)),
                'customer_name' => $data['customer_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cart as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'product_name' => $products[$id]->name,
                    'price' => $products[$id]->price,
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            session()->forget('cart');
            return redirect()->route('order.success', $order->order_number);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Order failed. Try again.');
        }
    }

    public function success($orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view('shop.success', compact('order'));
    }
}