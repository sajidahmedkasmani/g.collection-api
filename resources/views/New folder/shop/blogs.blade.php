@extends('layouts.app')
@section('title', 'The Journal — G. Collection')

@section('content')
@php
    $img = fn($p, $w = 800, $h = 1000) => "https://placehold.co/{$w}x{$h}/{$p->tone[0]}/{$p->tone[1]}?text=" . urlencode($p->category);
    $featured = $posts->first();
    $rest = $posts->slice(1)->values();
@endphp

{{-- ============ HEADER ============ --}}
<section class="max-w-7xl mx-auto px-6 pt-16 md:pt-24 pb-12">
    <p class="text-[11px] uppercase tracking-[0.2em] text-muted mb-6">The Journal</p>
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
        <h1 class="font-serif text-5xl md:text-8xl leading-[0.98]">Stories,<br><span class="italic text-plum">styling &amp; care.</span></h1>
        <p class="text-sm text-muted max-w-xs leading-relaxed">Guides, care tips and style notes from the G. Collection team in Karachi.</p>
    </div>
</section>

{{-- ============ FEATURED ============ --}}
@if($featured)
<section class="max-w-7xl mx-auto px-6 pb-16">
    <a href="{{ route('blog.show', $featured->slug) }}" class="group grid md:grid-cols-2 gap-0 rounded-[32px] overflow-hidden bg-plum text-white">
        <div class="aspect-[4/3] md:aspect-auto overflow-hidden bg-blush">
            <img src="{{ $img($featured, 1000, 800) }}" alt="{{ $featured->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy">
        </div>
        <div class="p-8 md:p-14 flex flex-col justify-center">
            <div class="flex items-center gap-3 text-[11px] uppercase tracking-[0.2em] text-white/60 mb-6">
                <span class="bg-rose text-white rounded-full px-3 py-1 tracking-widest">Latest</span>
                <span>{{ $featured->category }}</span>
            </div>
            <h2 class="font-serif text-3xl md:text-5xl leading-[1.08] mb-5">{{ $featured->title }}</h2>
            <p class="text-sm text-white/70 leading-relaxed mb-8 max-w-md">{{ $featured->excerpt }}</p>
            <div class="flex items-center justify-between text-xs text-white/60">
                <span>{{ $featured->dt->format('d M Y') }} · {{ $featured->read }} min read</span>
                <span class="inline-flex items-center gap-2 text-white group-hover:gap-4 transition-all">Read story <span>→</span></span>
            </div>
        </div>
    </a>
</section>
@endif

{{-- ============ GRID + FILTER ============ --}}
<section class="max-w-7xl mx-auto px-6 pb-24" x-data="{ f:'all' }">
    <div class="flex flex-wrap items-center gap-2 mb-10">
        <button @click="f='all'" :class="f==='all' ? 'bg-ink text-white border-ink' : 'border-black/15 text-ink/70 hover:border-plum hover:text-plum'" class="text-xs rounded-full border px-5 py-2 transition">All</button>
        @foreach($categories as $c)
            <button @click="f=@js($c)" :class="f===@js($c) ? 'bg-ink text-white border-ink' : 'border-black/15 text-ink/70 hover:border-plum hover:text-plum'" class="text-xs rounded-full border px-5 py-2 transition">{{ $c }}</button>
        @endforeach
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
        @foreach($rest as $i => $p)
            <a href="{{ route('blog.show', $p->slug) }}" x-show="f==='all' || f===@js($p->category)" x-transition.opacity class="group block {{ $i % 3 === 1 ? 'lg:mt-12' : '' }}">
                <div class="aspect-[4/5] overflow-hidden mb-5 bg-blush {{ $i % 2 === 0 ? 'rounded-t-[999px]' : 'rounded-3xl' }}">
                    <img src="{{ $img($p) }}" alt="{{ $p->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy">
                </div>
                <div class="flex items-center gap-3 text-[11px] uppercase tracking-[0.2em] text-muted mb-3">
                    <span class="text-plum">{{ $p->category }}</span>
                    <span>·</span>
                    <span>{{ $p->dt->format('d M Y') }}</span>
                </div>
                <h3 class="font-serif text-2xl leading-snug mb-2 group-hover:text-plum transition">{{ $p->title }}</h3>
                <p class="text-sm text-muted leading-relaxed line-clamp-2">{{ $p->excerpt }}</p>
                <p class="text-xs mt-4 inline-flex items-center gap-2 group-hover:gap-3 transition-all">{{ $p->read }} min read <span>→</span></p>
            </a>
        @endforeach
    </div>
</section>

{{-- ============ NEWSLETTER STRIP ============ --}}
<section class="bg-blush">
    <div class="max-w-7xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-10 items-center">
        <h2 class="font-serif text-4xl md:text-6xl leading-[1.02]">Never miss<br>a <span class="italic text-plum">new story.</span></h2>
        <form onsubmit="event.preventDefault(); alert('Thanks! (newsletter backend later)');">
            <div class="flex border-b border-ink">
                <input type="email" required placeholder="you@example.com" class="flex-1 bg-transparent py-4 text-lg focus:outline-none placeholder:text-black/30">
                <button class="bg-ink text-white text-xs rounded-full px-6 my-2 hover:bg-plum transition">Subscribe</button>
            </div>
            <p class="text-xs text-ink/60 mt-3">Just your email, nothing else.</p>
        </form>
    </div>
</section>
@endsection