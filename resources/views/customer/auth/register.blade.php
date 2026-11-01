@extends('frontend.layout.master')
@section('title', 'Create Account')

@section('content')
@php
    $input = 'w-full h-[52px] rounded-xl border bg-white px-4 text-base placeholder:text-black/30 focus:outline-none focus:border-plum focus:ring-4 focus:ring-rose/15 transition';
    $ok    = 'border-black/15';
    $bad   = 'border-red-400';
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 md:py-16">
    <div class="grid lg:grid-cols-2 lg:rounded-3xl lg:overflow-hidden lg:border lg:border-black/5 lg:bg-white lg:shadow-[0_30px_70px_-35px_rgba(91,26,74,.35)]">

        {{-- Brand panel (desktop only) --}}
        <aside class="hidden lg:flex relative bg-plum text-cream p-12 flex-col justify-between overflow-hidden">
            <div class="absolute -right-16 -bottom-10 w-72 h-96 arch bg-rose/25"></div>
            <div class="absolute right-24 -bottom-10 w-44 h-60 arch bg-cream/10"></div>
            <p class="relative font-serif text-2xl">G. Collection</p>
            <div class="relative">
                <h2 class="font-serif text-5xl leading-[1.05]">Your wardrobe,<br>your story.</h2>
                <p class="mt-4 text-cream/70 max-w-xs">Create an account to track orders and check out faster.</p>
            </div>
        </aside>

        {{-- Form --}}
        <section class="lg:p-14 flex flex-col justify-center">
            <div class="w-12 h-16 arch bg-blush mb-6 lg:hidden"></div>

            <h1 class="font-serif text-4xl md:text-5xl leading-tight">Create account</h1>
            <p class="text-muted mt-2 mb-8">It only takes a minute.</p>

            @if($errors->any())
                <div role="alert" class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3 mb-6">
                    <svg class="w-5 h-5 shrink-0 mt-px" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5v.01"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.register') }}" x-data="{ show:false, busy:false }" @submit="busy = true" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium mb-1.5">Full name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="{{ $input }} @error('name') {{ $bad }} @else {{ $ok }} @enderror">
                    @error('name') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', request('email')) }}" required
                           autocomplete="email" inputmode="email" autocapitalize="none" spellcheck="false" placeholder="you@example.com"
                           class="{{ $input }} @error('email') {{ $bad }} @else {{ $ok }} @enderror">
                    @error('email') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-sm font-medium mb-1.5">Phone</label>
                        <input id="phone" type="tel" inputmode="tel" name="phone" value="{{ old('phone') }}" required
                               autocomplete="tel" placeholder="03xx-xxxxxxx"
                               class="{{ $input }} @error('phone') {{ $bad }} @else {{ $ok }} @enderror">
                        @error('phone') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium mb-1.5">City <span class="text-muted font-normal">(optional)</span></label>
                        <input id="city" type="text" name="city" value="{{ old('city') }}" autocomplete="address-level2"
                               class="{{ $input }} @error('city') {{ $bad }} @else {{ $ok }} @enderror">
                        @error('city') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1.5">Password</label>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                               class="{{ $input }} pr-14 @error('password') {{ $bad }} @else {{ $ok }} @enderror">
                        <button type="button" @click="show = !show" :aria-pressed="show" :aria-label="show ? 'Hide passwords' : 'Show passwords'"
                                class="absolute right-1.5 top-1.5 w-10 h-10 rounded-lg flex items-center justify-center text-muted hover:text-plum hover:bg-cream transition">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1.5 12S5.5 5 12 5s10.5 7 10.5 7-4 7-10.5 7S1.5 12 1.5 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-cloak x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.9 17.9A10.6 10.6 0 0 1 12 19.5C5.5 19.5 1.5 12 1.5 12a18 18 0 0 1 4.6-5.4M9.9 4.7A10 10 0 0 1 12 4.5c6.5 0 10.5 7.5 10.5 7.5a18 18 0 0 1-2.1 3M1 1l22 22"/></svg>
                        </button>
                    </div>
                    @error('password') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-1.5">Confirm password</label>
                    <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                           class="{{ $input }} {{ $ok }}">
                </div>

                <button type="submit" :disabled="busy"
                        class="w-full h-[54px] flex items-center justify-center gap-3 bg-plum text-cream rounded-full font-medium text-base hover:bg-ink active:scale-[.99] disabled:opacity-70 disabled:cursor-wait transition">
                    <svg x-cloak x-show="busy" class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".3" stroke-width="3"/><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                    <span x-text="busy ? 'Creating your account…' : 'Create account'">Create account</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-black/10 text-center text-sm text-muted space-y-2">
                <p>Already have an account?
                    <a href="{{ route('customer.login') }}" class="text-plum font-medium underline underline-offset-4 hover:text-rose transition">Log in</a>
                </p>
                <p><a href="{{ route('shop') }}" class="hover:text-rose transition">Continue shopping as a guest →</a></p>
            </div>
        </section>
    </div>
</div>
@endsection