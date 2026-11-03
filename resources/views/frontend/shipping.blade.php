@extends('frontend.policies.layout')

@section('title', 'Shipping information')
@section('heading', 'Shipping information')
@section('lead', 'How your order gets from our Karachi studio to your door.')

@php
    // ── FILL THESE IN (plain text). Leave as null to hide that section. ──
    $charges  = null;   // delivery charges for orders under Rs. 10,000
    $delivery = null;   // estimated delivery time
    $areas    = null;   // where you deliver

    $details = array_filter([
        'Delivery charges'  => $charges,
        'Delivery time'     => $delivery,
        'Areas we deliver to' => $areas,
    ]);
@endphp

@section('policy')
    <div class="py-7 flex items-center gap-5">
        <div class="w-14 h-[72px] arch bg-blush flex items-end justify-center pb-3 shrink-0">
            <svg class="w-7 h-7 text-plum" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7h11v9H3zM14 10h4l3 3v3h-7M7 19.5a1.5 1.5 0 1 0 0-.01M17 19.5a1.5 1.5 0 1 0 0-.01"/></svg>
        </div>
        <div>
            <h2 class="font-serif text-2xl">Free shipping</h2>
            <p class="text-ink/80 mt-1">Orders of Rs. 10,000 or more ship free.</p>
        </div>
    </div>

    @forelse($details as $title => $text)
        <section class="py-7 border-t border-black/10">
            <h2 class="font-serif text-2xl mb-3">{{ $title }}</h2>
            <p class="text-[15px] leading-relaxed text-ink/80">{{ $text }}</p>
        </section>
    @empty
        <section class="py-7 border-t border-black/10">
            <h2 class="font-serif text-2xl mb-3">Charges &amp; delivery times</h2>
            <p class="text-[15px] leading-relaxed text-ink/80">Not sure about delivery charges or timing for your address? Message us on WhatsApp and we'll help you out.</p>
        </section>
    @endforelse

    <section class="py-7 border-t border-black/10">
        <h2 class="font-serif text-2xl mb-3">Paying for your order</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">We accept Cash on Delivery and Bank Transfer. You choose at checkout.</p>
    </section>

    <section class="py-7 border-t border-black/10">
        <h2 class="font-serif text-2xl mb-3">Something wrong with your delivery?</h2>
        <p class="text-[15px] leading-relaxed text-ink/80">Please <a href="https://wa.me/923347779231" class="text-plum underline underline-offset-4 hover:text-rose">contact us</a> and mention your order number. See also our <a href="{{ url('returns') }}" class="text-plum underline underline-offset-4 hover:text-rose">returns &amp; exchanges</a> page.</p>
    </section>
@endsection