@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

    @if(empty($cart))
        <div class="bg-white p-10 rounded-xl text-center shadow-sm">
            <p class="text-gray-500 mb-4">Your cart is empty.</p>
            <a href="{{ route('shop') }}" class="inline-block bg-rose-600 text-white px-6 py-2 rounded-lg">Start Shopping</a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @foreach($cart as $id => $item)
                <div class="flex items-center gap-4 p-4 border-b last:border-0">
                    <img src="{{ $item['image'] ?? 'https://placehold.co/100x100' }}" class="w-20 h-20 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-semibold">{{ $item['name'] }}</h3>
                        <p class="text-rose-600 font-bold">Rs. {{ number_format($item['price']) }}</p>
                    </div>
                    <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border rounded px-2 py-1 text-center" onchange="this.form.submit()">
                    </form>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <button class="text-red-500 text-sm hover:underline">Remove</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6 bg-white rounded-xl shadow-sm p-6 flex items-center justify-between">
            <p class="text-lg font-semibold">Total: <span class="text-rose-600">Rs. {{ number_format($total) }}</span></p>
            <a href="{{ route('checkout.index') }}" class="bg-rose-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-rose-700">Checkout</a>
        </div>
    @endif
</div>
@endsection