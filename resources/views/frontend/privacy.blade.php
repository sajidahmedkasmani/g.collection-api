@extends('frontend.policies.layout')

@section('title', 'Privacy policy')
@section('heading', 'Privacy policy')
@section('lead', 'What we collect, and why.')
@section('bg-text', 'Privacy')

<!-- @php
    // ── OPTIONAL: extra paragraph (e.g. data retention, third parties). null = hidden ──
    $extra = null;
@endphp -->

@section('policy')
    <section class="py-7">
        <h2 class="font-serif text-2xl mb-3">Information we collect</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">When you place an order or create an account, we collect the details you enter: your name, email, phone number, city, delivery address and any order notes.</p>
    </section>

    <section class="py-7 border-t border-black/10">
        <h2 class="font-serif text-2xl mb-3">How we use it</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">Your data is used only for order records and the shipping process.</p>
    </section>

    <section class="py-7 border-t border-black/10">
        <h2 class="font-serif text-2xl mb-3">Cart, login and cookies</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">We use essential cookies to keep your cart and your login working while you browse. Without them, checkout and your account would not function.</p>
    </section>

    @if($extra)
        <section class="py-7 border-t border-black/10">
            <h2 class="font-serif text-2xl mb-3">More information</h2>
            <p class="text-[15px] leading-relaxed text-ink/80">{{ $extra }}</p>
        </section>
    @endif

    <section class="py-7 border-t border-black/10">
        <h2 class="font-serif text-2xl mb-3">Questions about your data</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">Write to <a href="mailto:support@gdotcollection.com" class="text-plum underline underline-offset-4 hover:text-rose">support@gdotcollection.com</a> and we'll be glad to help.</p>
    </section>
@endsection