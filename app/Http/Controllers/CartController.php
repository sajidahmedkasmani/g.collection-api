<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\CartSummary;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $summary = CartSummary::for($cart);

        return view('frontend.shop.cart', compact('cart', 'summary'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $product = Product::where('is_active', true)->findOrFail($request->product_id);
        $cart = session()->get('cart', []);
        $qty = (int) ($request->quantity ?? 1);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = min(99, (int) $cart[$product->id]['quantity'] + $qty);
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $qty,
            ];
        }

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return $this->cartResponse($request, 'Product added to cart.');
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);
        $cart = session()->get('cart', []);
        if (isset($cart[$data['product_id']])) {
            $cart[$data['product_id']]['quantity'] = $data['quantity'];
            session()->put('cart', $cart);
        }

        if ($request->expectsJson()) {
            return $this->cartResponse($request, 'Cart updated.');
        }

        return back();
    }

    public function remove(Request $request)
    {
        $data = $request->validate(['product_id' => ['required', 'integer']]);
        $cart = session()->get('cart', []);
        unset($cart[$data['product_id']]);
        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return $this->cartResponse($request, 'Item removed from cart.');
        }

        return back();
    }

    private function cartResponse(Request $request, string $message)
    {
        $cart = session()->get('cart', []);
        $summary = CartSummary::for($cart);
        $viewData = compact('cart', 'summary');

        return response()->json([
            'message' => $message,
            'cart' => $cart,
            'summary' => $summary,
            'drawer_html' => view('frontend.partials.cart-drawer-content', $viewData)->render(),
            'page_html' => view('frontend.shop._cart_content', $viewData)->render(),
        ]);
    }
}