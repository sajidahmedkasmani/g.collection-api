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
<div class="bg-blush/40 border-b border-black/5">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 md:py-16">
        <nav class="text-sm text-muted mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-rose transition">Home</a>
            <span class="mx-2">/</span>
            <span class="text-ink">@yield('heading')</span>
        </nav>
        <h1 class="font-serif text-4xl md:text-6xl leading-[1.05]">@yield('heading')</h1>
        <p class="mt-4 text-muted max-w-xl text-lg">@yield('lead')</p>
    </div>
</div>

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