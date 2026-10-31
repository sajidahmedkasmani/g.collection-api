@extends('frontend.layout.master')
@section('title', 'Checkout')

@section('content')
@php
    $customer  = auth('customer')->user();
    $freeShip  = 10000;
    $left      = max(0, $freeShip - $total);
    $input     = 'w-full rounded-xl border bg-white px-4 py-3 text-[15px] placeholder:text-black/30 focus:outline-none focus:border-plum focus:ring-4 focus:ring-rose/15 transition';
    $ok        = 'border-black/15';
    $bad       = 'border-red-400';
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 pt-8 md:pt-14 pb-12">

    {{-- Breadcrumb + title --}}
    <nav class="text-sm text-muted mb-3" aria-label="Breadcrumb">
        <a href="{{ route('cart.index') }}" class="hover:text-rose transition">Cart</a>
        <span class="mx-2">/</span>
        <span class="text-ink">Checkout</span>
    </nav>
    <h1 class="font-serif text-4xl md:text-5xl mb-8">Checkout</h1>

    {{-- Guest / logged-in notice --}}
    @guest('customer')
        <div class="rounded-2xl bg-blush/50 border border-rose/20 p-4 sm:p-5 mb-8 flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
            <p class="text-sm"><strong>Have an account?</strong> <span class="text-ink/70">Log in for faster checkout and loyalty rewards, or continue as a guest.</span></p>
            <div class="flex gap-3 shrink-0">
                <a href="{{ route('customer.login') }}" class="bg-plum text-cream text-sm rounded-full px-5 py-2 hover:bg-ink transition">Log in</a>
                <a href="{{ route('customer.register') }}" class="border border-black/20 text-sm rounded-full px-5 py-2 hover:bg-white transition">Create account</a>
            </div>
        </div>
    @endguest
    @auth('customer')
        <div class="rounded-2xl bg-green-50 border border-green-200 p-4 mb-8 text-sm text-green-800">
            Logged in as <strong>{{ $customer->name }}</strong>. Your saved details are filled in below.
        </div>
    @endauth

    <div class="grid lg:grid-cols-[1fr_420px] gap-8 lg:gap-14 items-start">

        {{-- ORDER SUMMARY (collapsible on mobile, sits on the right on desktop) --}}
        <aside class="lg:order-2 lg:sticky lg:top-24" x-data="{ open:false }">
            <button type="button" @click="open = !open" :aria-expanded="open"
                    class="lg:hidden w-full flex items-center justify-between bg-white border border-black/10 rounded-2xl px-5 py-4">
                <span class="flex items-center gap-2 text-sm font-medium">
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                    <span x-text="open ? 'Hide order summary' : 'Show order summary'"></span>
                </span>
                <span class="font-serif text-xl">Rs. {{ number_format($total) }}</span>
            </button>

            <div :class="open ? 'block' : 'hidden'" class="lg:!block mt-3 lg:mt-0 bg-white rounded-2xl border border-black/5 shadow-[0_10px_40px_-20px_rgba(91,26,74,.25)] p-6">
                <h2 class="font-serif text-2xl mb-5 hidden lg:block">Order summary</h2>

                <ul class="space-y-4">
                    @foreach($cart as $item)
                        <li class="flex items-center gap-4">
                            <div class="relative shrink-0">
                                <img src="{{ $item['image'] ?? 'https://placehold.co/100x130' }}" alt="" loading="lazy" class="w-14 h-[72px] object-cover rounded-lg bg-blush">
                                <span class="absolute -top-2 -right-2 min-w-5 h-5 px-1 rounded-full bg-plum text-cream text-[11px] flex items-center justify-center">{{ $item['quantity'] }}</span>
                            </div>
                            <p class="flex-1 text-sm leading-snug">{{ $item['name'] }}</p>
                            <p class="text-sm font-medium">Rs. {{ number_format($item['price'] * $item['quantity']) }}</p>
                        </li>
                    @endforeach
                </ul>

                <div class="flex items-center justify-between mt-6 pt-5 border-t border-black/10">
                    <span class="text-muted">Total</span>
                    <span class="font-serif text-3xl">Rs. {{ number_format($total) }}</span>
                </div>
                <p class="text-xs mt-3 {{ $left > 0 ? 'text-muted' : 'text-green-700' }}">
                    @if($left > 0) Add Rs. {{ number_format($left) }} more for free shipping @else Free shipping applied @endif
                </p>
                <a href="{{ route('cart.index') }}" class="inline-block mt-4 text-sm text-muted underline underline-offset-4 hover:text-rose transition">Edit cart</a>
            </div>
        </aside>

        {{-- FORM --}}
        <form action="{{ route('checkout.store') }}" method="POST" class="lg:order-1" x-data="{ busy:false }" @submit="busy = true">
            @csrf

            {{-- Contact --}}
            <fieldset class="mb-9">
                <legend class="font-serif text-2xl mb-4">Contact</legend>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium mb-1.5">Full name</label>
                        <input id="customer_name" type="text" name="customer_name" autocomplete="name" required
                               value="{{ old('customer_name', $customer->name ?? '') }}"
                               class="{{ $input }} @error('customer_name') {{ $bad }} @else {{ $ok }} @enderror">
                        @error('customer_name') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium mb-1.5">Phone</label>
                        <input id="phone" type="tel" inputmode="tel" name="phone" autocomplete="tel" required placeholder="03XX XXXXXXX"
                               value="{{ old('phone', $customer->phone ?? '') }}"
                               class="{{ $input }} @error('phone') {{ $bad }} @else {{ $ok }} @enderror">
                        @error('phone') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-medium mb-1.5">Email</label>
                        <input id="email" type="email" name="email" autocomplete="email" required
                               value="{{ old('email', $customer->email ?? '') }}"
                               class="{{ $input }} @error('email') {{ $bad }} @else {{ $ok }} @enderror">
                        @error('email') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- Delivery --}}
            <fieldset class="mb-9">
                <legend class="font-serif text-2xl mb-4">Delivery</legend>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-sm font-medium mb-1.5">Address</label>
                        <textarea id="address" name="address" rows="3" required autocomplete="street-address" placeholder="House / flat, street, area"
                                  class="{{ $input }} resize-none @error('address') {{ $bad }} @else {{ $ok }} @enderror">{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium mb-1.5">City</label>
                        <input id="city" type="text" name="city" autocomplete="address-level2" required
                               value="{{ old('city', $customer->city ?? '') }}"
                               class="{{ $input }} @error('city') {{ $bad }} @else {{ $ok }} @enderror">
                        @error('city') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium mb-1.5">Notes <span class="text-muted font-normal">(optional)</span></label>
                        <input id="notes" type="text" name="notes" value="{{ old('notes') }}" placeholder="Landmark, delivery timing…"
                               class="{{ $input }} {{ $ok }}">
                    </div>
                </div>
            </fieldset>

            {{-- Payment --}}
            <fieldset class="mb-8">
                <legend class="font-serif text-2xl mb-4">Payment</legend>
                <div class="space-y-3">
                    <label class="relative block cursor-pointer">
                        <input type="radio" name="payment_method" value="cod" class="peer sr-only" @checked(old('payment_method', 'cod') === 'cod')>
                        <div class="flex items-center gap-4 rounded-xl border border-black/15 bg-white p-4 pr-14 transition hover:border-plum/50 peer-checked:border-plum peer-checked:bg-blush/40 peer-checked:ring-4 peer-checked:ring-rose/15 peer-focus-visible:ring-4 peer-focus-visible:ring-plum/30">
                            <span class="w-10 h-10 rounded-full bg-blush flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-plum" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/></svg>
                            </span>
                            <span>
                                <span class="block font-medium">Cash on delivery</span>
                                <span class="block text-sm text-muted">Pay when your order arrives</span>
                            </span>
                        </div>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full border-2 border-black/25 bg-white transition-all peer-checked:border-[6px] peer-checked:border-plum"></span>
                    </label>

                    <label class="relative block cursor-pointer">
                        <input type="radio" name="payment_method" value="bank_transfer" class="peer sr-only" @checked(old('payment_method') === 'bank_transfer')>
                        <div class="flex items-center gap-4 rounded-xl border border-black/15 bg-white p-4 pr-14 transition hover:border-plum/50 peer-checked:border-plum peer-checked:bg-blush/40 peer-checked:ring-4 peer-checked:ring-rose/15 peer-focus-visible:ring-4 peer-focus-visible:ring-plum/30">
                            <span class="w-10 h-10 rounded-full bg-blush flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-plum" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 10 12 4l9 6M5 10v8M9.5 10v8M14.5 10v8M19 10v8M3 20h18"/></svg>
                            </span>
                            <span>
                                <span class="block font-medium">Bank transfer</span>
                                <span class="block text-sm text-muted">Pay directly from your bank account</span>
                            </span>
                        </div>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full border-2 border-black/25 bg-white transition-all peer-checked:border-[6px] peer-checked:border-plum"></span>
                    </label>
                </div>
                @error('payment_method') <p class="text-red-600 text-xs mt-2">{{ $message }}</p> @enderror
            </fieldset>

            {{-- Submit (sticks to the bottom of the screen on mobile) --}}
            <div class="sticky bottom-0 -mx-4 px-4 py-3 bg-cream/95 backdrop-blur border-t border-black/10 lg:static lg:mx-0 lg:px-0 lg:py-0 lg:bg-transparent lg:border-0 lg:backdrop-blur-none"
                 style="padding-bottom: max(.75rem, env(safe-area-inset-bottom))">
                <button type="submit" :disabled="busy"
                        class="w-full flex items-center justify-center gap-3 bg-plum text-cream py-4 rounded-full font-medium text-base hover:bg-ink disabled:opacity-70 disabled:cursor-wait transition">
                    <svg x-cloak x-show="busy" class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".3" stroke-width="3"/><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                    <span x-text="busy ? 'Placing your order…' : 'Place order · Rs. {{ number_format($total) }}'">Place order · Rs. {{ number_format($total) }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection