<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    // public function home()
    // {
    //     $products = Product::where('is_active', true)->latest()->take(8)->get();
    //     return view('shop.home', compact('products'));
    // }

    public function index()
    {
        $products = Product::where('is_active', true)->latest()->paginate(12);
        return view('shop.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $related = Product::where('is_active', true)->where('id', '!=', $product->id)->take(4)->get();
        return view('shop.show', compact('product', 'related'));
    }


    public function home()
    {
        $sliders = \App\Models\HomeSlider::active()->get();
        $products = Product::where('is_active', true)->latest()->take(8)->get();
        return view('shop.home', compact('products', 'sliders'));
    }

}