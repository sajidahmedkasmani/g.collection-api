<!-- @extends('frontend.policies.layout') -->
@extends('frontend.layout.master')

@section('title', 'Terms & conditions')
@section('heading', 'Terms & conditions')
@section('lead', 'The simple ground rules for shopping with G. Collection.')

@php
    // ── OPTIONAL: extra terms, one per line. Empty array = hidden ──
    $otherTerms = [];
@endphp

@section('content')
    <section class="py-7">
        <h2 class="font-serif text-2xl mb-3">Orders</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">Orders are confirmed by the store after you submit them.</p>
    </section>

    <section class="py-7 border-t border-black/10">
        <h2 class="font-serif text-2xl mb-3">Payments</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">We support Cash on Delivery and Bank Transfer.</p>
    </section>

    <section class="py-7 border-t border-black/10">
        <h2 class="font-serif text-2xl mb-3">Your account</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">If you create an account, please keep your login details safe and make sure the information you give us is accurate.</p>
    </section>

    <section class="py-7 border-t border-black/10">
        <h2 class="font-serif text-2xl mb-3">Shipping, returns &amp; privacy</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">
            These are covered on their own pages:
            <a href="{{ url('shipping') }}" class="text-plum underline underline-offset-4 hover:text-rose">Shipping</a>,
            <a href="{{ url('returns') }}" class="text-plum underline underline-offset-4 hover:text-rose">Returns &amp; exchanges</a> and
            <a href="{{ url('privacy') }}" class="text-plum underline underline-offset-4 hover:text-rose">Privacy policy</a>.
        </p>
    </section>

    @if(count($otherTerms))
        <section class="py-7 border-t border-black/10">
            <h2 class="font-serif text-2xl mb-3">Other terms</h2>
            <ul class="list-disc pl-5 space-y-2 text-[15px] leading-relaxed text-ink/80">
                @foreach($otherTerms as $term) <li>{{ $term }}</li> @endforeach
            </ul>
        </section>
    @endif
@endsection