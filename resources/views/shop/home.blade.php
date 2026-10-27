@extends('layouts.app')
@section('title', 'G. Collection — Home')

@section('content')
<section class="bg-gradient-to-r from-rose-50 to-pink-100">
    <div class="max-w-7xl mx-auto px-4 py-20 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Elegant Fashion, <span class="text-rose-600">Redefined</span></h1>
        <p class="text-gray-600 mb-8 max-w-xl mx-auto">Discover premium collections curated just for you.</p>
        <a href="{{ route('shop') }}" class="inline-block bg-rose-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-rose-700 transition">Shop Now</a>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-16">
    <h2 class="text-2xl font-bold mb-8">Featured Products</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($products as $p)
            <a href="{{ route('product.show', $p->slug) }}" class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden">
                <div class="aspect-square bg-gray-100 overflow-hidden">
                    <img src="{{ $p->image ?? 'https://placehold.co/400x400?text=Product' }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-sm truncate">{{ $p->name }}</h3>
                    <p class="text-rose-600 font-bold mt-1">Rs. {{ number_format($p->price) }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endsection