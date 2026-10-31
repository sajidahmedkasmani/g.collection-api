@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-2 gap-10">
    <div class="bg-white rounded-xl overflow-hidden shadow-sm">
        <img src="{{ $product->image ?? 'https://placehold.co/600x600?text=Product' }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
    </div>
    <div>
        <h1 class="text-3xl font-bold mb-3">{{ $product->name }}</h1>
        <p class="text-2xl text-rose-600 font-bold mb-6">Rs. {{ number_format($product->price) }}</p>
        <p class="text-gray-600 mb-8 leading-relaxed">{{ $product->description ?? 'No description available.' }}</p>

        <form action="{{ route('cart.add') }}" method="POST" class="flex items-center gap-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="number" name="quantity" value="1" min="1" class="w-20 border rounded-lg px-3 py-2 text-center">
            <button class="bg-rose-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-rose-700 transition">Add to Cart</button>
        </form>
    </div>
</div>

@if($related->count())
<div class="max-w-7xl mx-auto px-4 pb-16">
    <h2 class="text-2xl font-bold mb-6">You may also like</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($related as $p)
            <a href="{{ route('product.show', $p->slug) }}" class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden">
                <div class="aspect-square bg-gray-100">
                    <img src="{{ $p->image ?? 'https://placehold.co/400x400?text=Product' }}" class="w-full h-full object-cover" loading="lazy">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-sm truncate">{{ $p->name }}</h3>
                    <p class="text-rose-600 font-bold mt-1">Rs. {{ number_format($p->price) }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif
@endsection