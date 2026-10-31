<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    public function about()
    {
        return view('shop.about');
    }
    public function blogs()
    {
        return view('shop.blogs');
    }
    public function contact()
    {
        return view('shop.contact');
    }


    public function send(Request $request)
    {
        // Honeypot: bots fill this hidden field, real people never see it.
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('contact_sent', true);
        }

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:60'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $body = "New contact message\n\n"
            . "Name: {$data['name']}\n"
            . "Email: {$data['email']}\n"
            . "Phone: " . ($data['phone'] ?? '-') . "\n"
            . "Subject: {$data['subject']}\n\n"
            . $data['message'];

        // Always keep a copy in the log so nothing is lost if mail isn't configured yet.
        Log::info('Contact form', $data);

        try {
            Mail::raw($body, function ($m) use ($data) {
                $m->to('support@gdotcollection.com')
                  ->replyTo($data['email'], $data['name'])
                  ->subject('[G. Collection] ' . $data['subject'] . ' — ' . $data['name']);
            });
        } catch (\Throwable $e) {
            Log::warning('Contact mail failed: ' . $e->getMessage());
        }

        return redirect()->route('contact')->with('contact_sent', true);
    }

}