@extends('layouts.app')
@section('title', 'Shop — G. Collection')

@section('content')
@php
    if (method_exists($products, 'withQueryString')) { $products = $products->withQueryString(); }

    $cat   = request('category');
    $q     = trim((string) request('q'));
    $sort  = request('sort', 'newest');
    $cats  = ['' => 'All', 'stitched' => 'Stitched', 'unstitched' => 'Unstitched', 'accessories' => 'Accessories'];
    $total = method_exists($products, 'total') ? $products->total() : $products->count();
    $heading = $cat ? ($cats[$cat] ?? ucfirst($cat)) : 'The Collection';
    $chipUrl = fn($c) => route('shop', array_filter(['category' => $c, 'q' => $q, 'sort' => request('sort')]));
    $paged = method_exists($products, 'hasPages') && $products->hasPages();
@endphp

{{-- ============ HEADER BAND ============ --}}
<section class="bg-blush relative overflow-hidden">
    <p class="absolute -bottom-10 right-0 font-serif italic text-[160px] md:text-[240px] leading-none text-white/40 select-none pointer-events-none">Shop</p>
    <div class="max-w-7xl mx-auto px-5 md:px-6 pt-8 md:pt-14 pb-10 md:pb-14 relative">
        <nav class="text-[11px] uppercase tracking-[0.2em] text-plum/70 mb-5 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-plum">Home</a><span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-plum {{ $cat ? '' : 'text-plum' }}">Shop</a>
            @if($cat)<span>/</span><span class="text-plum">{{ $cats[$cat] ?? ucfirst($cat) }}</span>@endif
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
                @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                <svg class="w-4 h-4 text-muted shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>
                <input type="search" name="q" value="{{ $q }}" placeholder="Search kurtas, lace, lawn..." class="flex-1 bg-transparent text-sm py-2 focus:outline-none placeholder:text-black/30 min-w-0">
                <button class="bg-plum text-white text-xs rounded-full px-5 py-2.5 hover:bg-ink transition">Search</button>
            </form>
        </div>
    </div>
</section>

{{-- ============ FILTER BAR (sticky) ============ --}}
<div class="sticky top-[65px] z-40 bg-cream/90 backdrop-blur border-b border-black/10">
    <div class="max-w-7xl mx-auto px-5 md:px-6 py-3 flex items-center justify-between gap-4">
        <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-1 px-1 flex-1">
            @foreach($cats as $slug => $label)
                @php $on = (string) $cat === (string) $slug; @endphp
                <a href="{{ $chipUrl($slug ?: null) }}"
                   class="shrink-0 text-xs rounded-full border px-5 py-2.5 transition {{ $on ? 'bg-plum text-white border-plum' : 'border-black/15 text-ink/70 hover:border-plum hover:text-plum' }}">{{ $label }}</a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('shop') }}" class="shrink-0">
            @if($cat)<input type="hidden" name="category" value="{{ $cat }}">@endif
            @if($q)<input type="hidden" name="q" value="{{ $q }}">@endif
            <label class="sr-only" for="sort">Sort</label>
            <select id="sort" name="sort" onchange="this.form.submit()"
                    class="text-xs bg-white border border-black/15 rounded-full px-4 py-2.5 focus:outline-none focus:border-plum cursor-pointer">
                <option value="newest"     {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="price_asc"  {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: low → high</option>
                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: high → low</option>
            </select>
        </form>
    </div>
</div>

{{-- ============ GRID ============ --}}
<section class="max-w-7xl mx-auto px-5 md:px-6 py-10 md:py-14">
    @if($products->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-3 sm:gap-x-5 md:gap-x-6 gap-y-9 md:gap-y-12">
            @foreach($products as $p)
                @include('partials.product-card', ['p' => $p])
            @endforeach
        </div>

        {{-- pagination --}}
        @if($paged)
            @php
                $cur = $products->currentPage(); $last = $products->lastPage();
                $from = max(1, $cur - 1); $to = min($last, $cur + 1);
            @endphp
            <nav class="mt-14 flex items-center justify-center gap-2 text-sm" aria-label="Pagination">
                @if($products->onFirstPage())
                    <span class="px-5 py-2.5 rounded-full border border-black/10 text-black/25">← Prev</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="px-5 py-2.5 rounded-full border border-black/15 hover:bg-plum hover:text-white hover:border-plum transition">← Prev</a>
                @endif

                <div class="hidden sm:flex items-center gap-1">
                    @if($from > 1)
                        <a href="{{ $products->url(1) }}" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-blush transition">1</a>
                        @if($from > 2)<span class="px-1 text-muted">…</span>@endif
                    @endif
                    @for($i = $from; $i <= $to; $i++)
                        <a href="{{ $products->url($i) }}" class="w-10 h-10 rounded-full flex items-center justify-center transition {{ $i === $cur ? 'bg-plum text-white' : 'hover:bg-blush' }}">{{ $i }}</a>
                    @endfor
                    @if($to < $last)
                        @if($to < $last - 1)<span class="px-1 text-muted">…</span>@endif
                        <a href="{{ $products->url($last) }}" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-blush transition">{{ $last }}</a>
                    @endif
                </div>
                <span class="sm:hidden text-xs text-muted px-2">Page {{ $cur }} of {{ $last }}</span>

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="px-5 py-2.5 rounded-full border border-black/15 hover:bg-plum hover:text-white hover:border-plum transition">Next →</a>
                @else
                    <span class="px-5 py-2.5 rounded-full border border-black/10 text-black/25">Next →</span>
                @endif
            </nav>
        @endif
    @else
        <div class="text-center py-20 md:py-28">
            <div class="w-24 h-32 mx-auto rounded-t-full bg-blush flex items-center justify-center font-serif italic text-plum text-5xl mb-8">G.</div>
            <h2 class="font-serif text-3xl md:text-4xl mb-3">Nothing here <span class="italic text-plum">yet.</span></h2>
            <p class="text-sm text-muted max-w-sm mx-auto mb-8">
                @if($q) We couldn't find anything for “{{ $q }}”. Try a different word or browse everything. @else New pieces are on their way. @endif
            </p>
            <a href="{{ route('shop') }}" class="inline-block bg-plum text-white text-xs rounded-full px-8 py-3.5 hover:bg-ink transition">View all products</a>
        </div>
    @endif
</section>

{{-- ============ PROMISE STRIP ============ --}}
<section class="bg-ink text-cream">
    <div class="max-w-7xl mx-auto px-5 md:px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-6 text-center md:text-left">
        @foreach([
            ['Free shipping','On Rs. 10,000 and above'],
            ['Cash on Delivery','Or bank transfer'],
            ['Easy exchanges','WhatsApp within 48 hours'],
            ['Made in Karachi','Stitched & unstitched'],
        ] as [$t,$d])
            <div>
                <p class="font-serif text-lg md:text-xl">{{ $t }}</p>
                <p class="text-xs text-cream/60 mt-1">{{ $d }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection