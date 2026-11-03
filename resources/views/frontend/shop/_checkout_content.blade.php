<div class="max-w-5xl mx-auto px-4 py-10">
    @guest('customer')
        <div class="bg-gray-50 border rounded-xl p-4 mb-6">
            <p class="text-sm text-gray-600 mb-3"><strong>Have an account?</strong> Login for faster checkout and loyalty rewards.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('customer.login') }}" class="bg-black text-white text-xs rounded-lg px-4 py-2 hover:opacity-80">Login</a>
                <a href="{{ route('customer.register') }}" class="border border-gray-300 text-xs rounded-lg px-4 py-2 hover:bg-gray-100">Create Account</a>
                <span class="text-xs text-gray-400 self-center">or continue as guest below</span>
            </div>
        </div>
    @endguest

    @auth('customer')
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 text-sm text-green-800">
            Logged in as <strong>{{ auth('customer')->user()->name }}</strong>. Your details have been pre-filled below.
        </div>
    @endauth

    <div class="grid md:grid-cols-5 gap-8">
        <div class="md:col-span-3 bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold mb-6">Shipping Details</h2>
            <form action="{{ route('checkout.store') }}" method="POST" data-checkout-form>
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-sm font-medium" for="checkout-customer-name">Full Name</label>
                        <input id="checkout-customer-name" type="text" name="customer_name" value="{{ old('customer_name', auth('customer')->user()->name ?? '') }}" required class="w-full border rounded-lg px-3 py-2">
                        <p class="text-red-500 text-xs mt-1" data-checkout-error="customer_name"></p>
                        @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium" for="checkout-email">Email</label>
                        <input id="checkout-email" type="email" name="email" value="{{ old('email', auth('customer')->user()->email ?? '') }}" required class="w-full border rounded-lg px-3 py-2">
                        <p class="text-red-500 text-xs mt-1" data-checkout-error="email"></p>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium" for="checkout-phone">Phone</label>
                        <input id="checkout-phone" type="text" name="phone" value="{{ old('phone') }}" required class="w-full border rounded-lg px-3 py-2">
                        <p class="text-red-500 text-xs mt-1" data-checkout-error="phone"></p>
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium" for="checkout-city">City</label>
                        <input id="checkout-city" type="text" name="city" value="{{ old('city') }}" required class="w-full border rounded-lg px-3 py-2">
                        <p class="text-red-500 text-xs mt-1" data-checkout-error="city"></p>
                        @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium" for="checkout-address">Address</label>
                    <textarea id="checkout-address" name="address" required rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('address') }}</textarea>
                    <p class="text-red-500 text-xs mt-1" data-checkout-error="address"></p>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="text-sm font-medium" for="checkout-notes">Notes (optional)</label>
                    <textarea id="checkout-notes" name="notes" rows="2" class="w-full border rounded-lg px-3 py-2">{{ old('notes') }}</textarea>
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
                    <p class="text-red-500 text-xs" data-checkout-error="payment_method"></p>
                    @error('payment_method') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="mt-6 w-full bg-rose-600 text-white py-3 rounded-lg font-bold hover:bg-rose-700">
                    Place Order
                </button>
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
            <div class="space-y-2 border-t mt-3 pt-3 text-sm">
                <div class="flex justify-between"><span>Subtotal</span><span>Rs. {{ number_format($summary['subtotal']) }}</span></div>
                <div class="flex justify-between"><span>Shipping</span><span>{{ $summary['shipping'] > 0 ? 'Rs. '.number_format($summary['shipping']) : 'Free' }}</span></div>
            </div>
            <div class="flex justify-between mt-4 border-t pt-4 font-bold text-lg">
                <span>Total</span>
                <span class="text-rose-600">Rs. {{ number_format($summary['total']) }}</span>
            </div>
        </div>
    </div>
</div>
