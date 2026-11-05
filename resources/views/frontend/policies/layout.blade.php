@extends('frontend.layout.master')

@section('content')
@php
    $policyPages = [
        ['shipping', 'Shipping information'],
        ['returns',  'Returns & exchanges'],
        ['privacy',  'Privacy policy'],
        ['terms',    'Terms & conditions'],
    ];
@endphp

{{-- Page header --}}
<!-- <div class="bg-blush/40 border-b border-black/5">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 md:py-16">
        <nav class="text-sm text-muted mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-rose transition">Home</a>
            <span class="mx-2">/</span>
            <span class="text-ink">@yield('heading')</span>
        </nav>
        <h1 class="font-serif text-4xl md:text-6xl leading-[1.05]">@yield('heading')</h1>
        <p class="mt-4 text-muted max-w-xl text-lg">@yield('lead')</p>
    </div>
</div> -->

{{-- ============ HEADER BAND ============ --}}
<section class="bg-blush relative overflow-hidden">
    <p class="absolute -bottom-10 right-0 font-serif italic text-[160px] md:text-[240px] leading-none text-white/40 select-none pointer-events-none">Shop</p>
    <div class="max-w-7xl mx-auto px-5 md:px-6 pt-8 md:pt-14 pb-10 md:pb-14 relative">
        <nav class="text-[11px] uppercase tracking-[0.2em] text-plum/70 mb-5 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-plum">Home</a><span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-plum {{ $cat || $collection ? '' : 'text-plum' }}">Shop</a>
            @if($selectedCollection)<span>/</span><span>{{ $selectedCollection->name }}</span>@endif
            @if($selectedCategory)<span>/</span><span class="text-plum">{{ $selectedCategory->name }}</span>@endif
        </nav>

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <h1 class="font-serif text-5xl md:text-7xl leading-[0.98]">{{ $heading }}</h1>
                <p class="text-sm text-ink/60 mt-4">
                    {{ $total }} {{ \Illuminate\Support\Str::plural('piece', $total) }}
                    @if($q) for “{{ $q }}” @endif
                </p>
            </div>

            <form method="GET" action="{{ route('shop') }}" class="flex items-center gap-2 bg-white rounded-full pl-5 pr-1.5 py-1.5 w-full md:w-80 shadow-[0_10px_30px_-18px_rgba(91,26,74,.5)] focus-within:ring-4 ring-plum/10 transition">
                @if($cat)<input type="hidden" name="category" value="{{ $cat }}">@endif
                @if($collection)<input type="hidden" name="collection" value="{{ $collection }}">@endif
                @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                <svg class="w-4 h-4 text-muted shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>
                <input type="search" name="q" value="{{ $q }}" placeholder="Search kurtas, lace, lawn..." class="flex-1 bg-transparent text-sm py-2 focus:outline-none placeholder:text-black/30 min-w-0">
                <button class="bg-plum text-white text-xs rounded-full px-5 py-2.5 hover:bg-ink transition">Search</button>
            </form>
        </div>
    </div>
</section>

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 md:py-14">
    <div class="grid md:grid-cols-[210px_1fr] gap-8 md:gap-14 items-start">

        {{-- Policy navigation: chips on mobile, sticky list on desktop --}}
        <nav aria-label="Policies" class="md:sticky md:top-24">
            <ul class="flex md:flex-col gap-2 md:gap-1 overflow-x-auto no-scrollbar -mx-4 px-4 md:mx-0 md:px-0 pb-1">
                @foreach($policyPages as [$path, $label])
                    @php $active = request()->is($path); @endphp
                    <li class="shrink-0">
                        <a href="{{ url($path) }}" @if($active) aria-current="page" @endif
                           class="block whitespace-nowrap rounded-full md:rounded-lg px-4 py-2 md:py-2.5 text-sm border md:border-0 transition
                                  {{ $active ? 'bg-plum text-cream border-plum' : 'border-black/15 hover:border-plum hover:text-plum md:hover:bg-blush/50' }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="min-w-0">
            <article class="bg-white rounded-2xl border border-black/5 px-6 sm:px-9 py-3">
                @yield('policy')
            </article>

            {{-- Help card --}}
            <div class="mt-8 rounded-2xl bg-plum text-cream p-6 sm:p-8">
                <h2 class="font-serif text-2xl">Need a hand?</h2>
                <p class="text-cream/70 text-sm mt-1">Our team is happy to help. Mon–Sat, 11:00 AM – 9:00 PM.</p>
                <div class="mt-5 flex flex-col sm:flex-row gap-3 text-sm">
                    <a href="https://wa.me/923347779231" class="text-center bg-cream text-plum font-medium rounded-full px-6 py-3 hover:bg-white transition">WhatsApp us</a>
                    <a href="tel:+923347779231" class="text-center border border-cream/30 rounded-full px-6 py-3 hover:bg-cream/10 transition">+92 334 7779231</a>
                    <a href="mailto:support@gdotcollection.com" class="text-center border border-cream/30 rounded-full px-6 py-3 hover:bg-cream/10 transition">Email support</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection