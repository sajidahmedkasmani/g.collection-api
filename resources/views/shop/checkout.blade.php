@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    {{-- Guest notice — login/register ka option --}}
    @guest('customer')
        <div class="bg-gray-50 border rounded-xl p-4 mb-6">
            <p class="text-sm text-gray-600 mb-3">
                <strong>Have an account?</strong> Login for faster checkout and loyalty rewards.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('customer.login') }}"
                   class="bg-black text-white text-xs rounded-lg px-4 py-2 hover:opacity-80">
                    Login
                </a>
                <a href="{{ route('customer.register') }}"
                   class="border border-gray-300 text-xs rounded-lg px-4 py-2 hover:bg-gray-100">
                    Create Account
                </a>
                <span class="text-xs text-gray-400 self-center">or continue as guest below</span>
            </div>
        </div>
    @endguest

    {{-- Logged-in customer greeting --}}
    @auth('customer')
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 text-sm text-green-800">
            Logged in as <strong>{{ auth('customer')->user()->name }}</strong>.
            Your details have been pre-filled below.
        </div>
    @endauth

    <div class="grid md:grid-cols-5 gap-8">
        {{-- Shipping form --}}
        <div class="md:col-span-3 bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold mb-6">Shipping Details</h2>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-sm font-medium">Full Name</label>
                        <input type="text" name="customer_name"
                               value="{{ old('customer_name', auth('customer')->user()->name ?? '') }}"
                               required class="w-full border rounded-lg px-3 py-2">
                        @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium">Email</label>
                        <input type="email" name="email"
                               value="{{ old('email', auth('customer')->user()->email ?? '') }}"
                               required class="w-full border rounded-lg px-3 py-2">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               required class="w-full border rounded-lg px-3 py-2">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium">City</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                               required class="w-full border rounded-lg px-3 py-2">
                        @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium">Address</label>
                    <textarea name="address" required rows="3"
                              class="w-full border rounded-lg px-3 py-2">{{ old('address') }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium">Notes (optional)</label>
                    <textarea name="notes" rows="2"
                              class="w-full border rounded-lg px-3 py-2">{{ old('notes') }}</textarea>
                </div>

                <h3 class="text-lg font-bold mt-6 mb-3">Payment Method</h3>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 border rounded-lg p-3 cursor-pointer hover:border-rose-500">
                        <input type="radio" name="payment_method" value="cod"
                               @checked(old('payment_method', 'cod') === 'cod')>
                        <span class="font-medium">Cash on Delivery (COD)</span>
                    </label>
                    <label class="flex items-center gap-3 border rounded-lg p-3 cursor-pointer hover:border-rose-500">
                        <input type="radio" name="payment_method" value="bank_transfer"
                               @checked(old('payment_method') === 'bank_transfer')>
                        <span class="font-medium">Bank Transfer</span>
                    </label>
                </div>

                <button type="submit"
                        class="mt-6 w-full bg-rose-600 text-white py-3 rounded-lg font-bold hover:bg-rose-700">
                    Place Order
                </button>
            </form>
        </div>

        {{-- Order summary --}}
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
</div>
@endsection