@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10 grid md:grid-cols-5 gap-8">
    <div class="md:col-span-3 bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold mb-6">Shipping Details</h2>
        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-sm font-medium">Full Name</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required class="w-full border rounded-lg px-3 py-2">
                    @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-3 py-2">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full border rounded-lg px-3 py-2">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">City</label>
                    <input type="text" name="city" value="{{ old('city') }}" required class="w-full border rounded-lg px-3 py-2">
                    @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mb-4">
                <label class="text-sm font-medium">Address</label>
                <textarea name="address" required rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('address') }}</textarea>
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label class="text-sm font-medium">Notes (optional)</label>
                <textarea name="notes" rows="2" class="w-full border rounded-lg px-3 py-2">{{ old('notes') }}</textarea>
            </div>

            <h3 class="text-lg font-bold mt-6 mb-3">Payment Method</h3>
            <div class="space-y-2">
                <label class="flex items-center gap-3 border rounded-lg p-3 cursor-pointer hover:border-rose-500">
                    <input type="radio" name="payment_method" value="cod" @checked(old('payment_method', 'cod') === 'cod')>
                    <span class="font-medium">Cash on Delivery (COD)</span>
                </label>
                <label class="flex items-center gap-3 border rounded-lg p-3 cursor-pointer hover:border-rose-500">
                    <input type="radio" name="payment_method" value="bank_transfer" @checked(old('payment_method') === 'bank_transfer')>
                    <span class="font-medium">Bank Transfer</span>
                </label>
            </div>

            <button class="mt-6 w-full bg-rose-600 text-white py-3 rounded-lg font-bold hover:bg-rose-700">Place Order</button>
        </form>
    </div>

    <div class="md:col-span-2 bg-white rounded-xl shadow-sm p-6 h-fit">
        <h2 class="text-xl font-bold mb-4">Order Summary</h2>
        @foreach($cart as $item)
            <div class="flex justify-between py-2 border-b last:border-0 text-sm">
                <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                <span>Rs. {{ number_format($item['price'] * $item['quantity']) }}</span>
            </div>
        @endforeach
        <div class="flex justify-between mt-4 font-bold text-lg">
            <span>Total</span>
            <span class="text-rose-600">Rs. {{ number_format($total) }}</span>
        </div>
    </div>
</div>
@endsection