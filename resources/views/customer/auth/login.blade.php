@extends('frontend.layout.master')

@section('title', 'Log in')

@section('content')
@php
    $input = 'w-full h-[52px] rounded-xl border bg-white px-4 text-base placeholder:text-black/30 focus:outline-none focus:border-plum focus:ring-4 focus:ring-rose/15 transition';
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 md:py-16">
    <div class="grid lg:grid-cols-2 lg:rounded-3xl lg:overflow-hidden lg:border lg:border-black/5 lg:bg-white lg:shadow-[0_30px_70px_-35px_rgba(91,26,74,.35)]">

        {{-- Brand panel (desktop only) --}}
        <aside class="hidden lg:flex relative bg-plum text-cream p-12 flex-col justify-between overflow-hidden">
            <div class="absolute -right-16 -bottom-10 w-72 h-96 arch bg-rose/25"></div>
            <div class="absolute right-24 -bottom-10 w-44 h-60 arch bg-cream/10"></div>
            <p class="relative font-serif text-2xl">G. Collection</p>
            <div class="relative">
                <h2 class="font-serif text-5xl leading-[1.05]">Wear the story,<br>every stitch.</h2>
                <p class="mt-4 text-cream/70 max-w-xs">Log in to track your orders and check out faster.</p>
            </div>
        </aside>

        {{-- Form --}}
        <section class="lg:p-14 flex flex-col justify-center">
            <div class="w-12 h-16 arch bg-blush mb-6 lg:hidden"></div>

            <h1 class="font-serif text-4xl md:text-5xl leading-tight">Welcome back</h1>
            <p class="text-muted mt-2 mb-8">Log in to your customer account.</p>

            @if($errors->any())
                <div role="alert" class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3 mb-6">
                    <svg class="w-5 h-5 shrink-0 mt-px" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5v.01"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.login') }}" x-data="{ show:false, busy:false }" @submit="busy = true" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           autocomplete="username" inputmode="email" autocapitalize="none" spellcheck="false" placeholder="you@example.com"
                           class="{{ $input }} {{ $errors->has('email') ? 'border-red-400' : 'border-black/15' }}">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1.5">Password</label>
                    <div class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                               autocomplete="current-password" placeholder="Enter your password"
                               class="{{ $input }} pr-14 {{ $errors->has('password') ? 'border-red-400' : 'border-black/15' }}">
                        <button type="button" @click="show = !show" :aria-pressed="show" :aria-label="show ? 'Hide password' : 'Show password'"
                                class="absolute right-1.5 top-1.5 w-10 h-10 rounded-lg flex items-center justify-center text-muted hover:text-plum hover:bg-cream transition">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1.5 12S5.5 5 12 5s10.5 7 10.5 7-4 7-10.5 7S1.5 12 1.5 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg x-cloak x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.9 17.9A10.6 10.6 0 0 1 12 19.5C5.5 19.5 1.5 12 1.5 12a18 18 0 0 1 4.6-5.4M9.9 4.7A10 10 0 0 1 12 4.5c6.5 0 10.5 7.5 10.5 7.5a18 18 0 0 1-2.1 3M1 1l22 22"/></svg>
                        </button>
                    </div>
                </div>

                <label class="relative inline-flex items-center gap-3 cursor-pointer select-none min-h-[44px]">
                    <input type="checkbox" name="remember" class="peer sr-only" @checked(old('remember'))>
                    <span class="w-[22px] h-[22px] rounded-md border-2 border-black/25 bg-white flex items-center justify-center transition peer-checked:bg-plum peer-checked:border-plum peer-focus-visible:ring-4 peer-focus-visible:ring-plum/30">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 12l5 5L20 6"/></svg>
                    </span>
                    <span class="text-sm">Keep me logged in</span>
                </label>

                <button type="submit" :disabled="busy"
                        class="w-full h-[54px] flex items-center justify-center gap-3 bg-plum text-cream rounded-full font-medium text-base hover:bg-ink active:scale-[.99] disabled:opacity-70 disabled:cursor-wait transition">
                    <svg x-cloak x-show="busy" class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".3" stroke-width="3"/><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                    <span x-text="busy ? 'Logging in…' : 'Log in'">Log in</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-black/10 text-center text-sm text-muted space-y-2">
                <p>New to G. Collection?
                    <a href="{{ route('customer.register') }}" class="text-plum font-medium underline underline-offset-4 hover:text-rose transition">Create an account</a>
                </p>
                <p><a href="{{ route('shop') }}" class="hover:text-rose transition">Continue shopping as a guest →</a></p>
            </div>
        </section>
    </div>
</div>
@endsection