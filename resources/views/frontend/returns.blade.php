@extends('frontend.policies.layout')

@section('title', 'Returns & exchanges')
@section('heading', 'Returns & exchanges')
@section('lead', 'Something not quite right? Reach out and we will sort it together.')
@section('bg-text', 'Returns')

@php
    // ── FILL THESE IN (plain text). Leave as null to hide that section. ──
    $window    = null;  // how long after delivery a return/exchange can be requested
    $condition = null;  // condition items must be in (unworn, tags attached, etc.)
    $process   = null;  // how refunds / exchanges are handled

    $details = array_filter([
        'Eligibility window'  => $window,
        'Condition of items'  => $condition,
        'How it works'        => $process,
    ]);
@endphp

@section('policy')
    <section class="py-7">
        <h2 class="font-serif text-2xl mb-3">Start a return or exchange</h2>
        <p class="text-[15px] leading-relaxed text-ink/80 mb-4">Please contact us to start a return or exchange, and keep your order number handy.</p>
        <ul class="text-[15px] text-ink/80 space-y-1.5">
            <li>WhatsApp: <a href="https://wa.me/923347779231" class="text-plum underline underline-offset-4 hover:text-rose">+92 334 7779231</a></li>
            <li>Phone: <a href="tel:+923347779231" class="text-plum underline underline-offset-4 hover:text-rose">+92 334 7779231</a></li>
            <li>Email: <a href="mailto:support@gdotcollection.com" class="text-plum underline underline-offset-4 hover:text-rose">support@gdotcollection.com</a></li>
        </ul>
    </section>

    @forelse($details as $title => $text)
        <section class="py-7 border-t border-black/10">
            <h2 class="font-serif text-2xl mb-3">{{ $title }}</h2>
            <p class="text-[15px] leading-relaxed text-ink/80">{{ $text }}</p>
        </section>
    @empty
        <section class="py-7 border-t border-black/10">
            <h2 class="font-serif text-2xl mb-3">Eligibility</h2>
            <p class="text-[15px] leading-relaxed text-ink/80">Wondering whether your order qualifies? Contact us and we'll guide you.</p>
        </section>
    @endforelse
@endsection