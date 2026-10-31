@extends('layouts.app')
@section('title', $post->title . ' — G. Collection')

@section('content')
@php
    $img = fn($p, $w = 800, $h = 1000) => "https://placehold.co/{$w}x{$h}/{$p->tone[0]}/{$p->tone[1]}?text=" . urlencode($p->category);
    $url = url()->current();
    $waText = urlencode($post->title . ' — ' . $url);
@endphp

{{-- ============ HERO ============ --}}
<section class="bg-blush">
    <div class="max-w-4xl mx-auto px-6 pt-14 md:pt-20 pb-16 text-center">
        <a href="{{ route('blogs') }}" class="inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.2em] text-plum hover:gap-3 transition-all mb-8">← The Journal</a>
        <div class="flex items-center justify-center gap-3 text-[11px] uppercase tracking-[0.2em] text-ink/60 mb-6">
            <span class="bg-plum text-white rounded-full px-3 py-1">{{ $post->category }}</span>
            <span>{{ $post->dt->format('d M Y') }}</span>
            <span>·</span>
            <span>{{ $post->read }} min read</span>
        </div>
        <h1 class="font-serif text-4xl md:text-6xl leading-[1.05]">{{ $post->title }}</h1>
        <p class="text-base text-ink/70 max-w-xl mx-auto mt-6 leading-relaxed">{{ $post->excerpt }}</p>
    </div>
</section>

{{-- ============ COVER ============ --}}
<div class="max-w-5xl mx-auto px-6 -mt-2">
    <div class="aspect-[16/9] rounded-[32px] overflow-hidden bg-blush shadow-[0_30px_60px_-30px_rgba(91,26,74,.5)] -translate-y-0">
        <img src="{{ $img($post, 1600, 900) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
    </div>
</div>

{{-- ============ BODY ============ --}}
<article class="max-w-2xl mx-auto px-6 py-16">
    @foreach($post->body as [$type, $text])
        @if($type === 'h2')
            <h2 class="font-serif text-3xl md:text-4xl mt-12 mb-4">{{ $text }}</h2>
        @elseif($type === 'quote')
            <blockquote class="my-10 border-l-2 border-rose pl-6 font-serif italic text-2xl md:text-3xl leading-snug text-plum">{{ $text }}</blockquote>
        @else
            <p class="text-[17px] leading-[1.85] text-ink/80 mb-6">{{ $text }}</p>
        @endif
    @endforeach

    {{-- share --}}
    <div x-data="{ copied:false }" class="mt-14 pt-8 border-t border-black/10 flex flex-wrap items-center justify-between gap-4">
        <p class="text-[11px] uppercase tracking-[0.2em] text-muted">Share this story</p>
        <div class="flex gap-2">
            <a href="https://wa.me/?text={{ $waText }}" target="_blank" rel="noopener" class="text-xs rounded-full border border-black/15 px-5 py-2.5 hover:bg-rose hover:text-white hover:border-rose transition">WhatsApp</a>
            <button type="button" @click="navigator.clipboard.writeText(@js($url)); copied=true; setTimeout(()=>copied=false,1800)"
                    class="text-xs rounded-full border border-black/15 px-5 py-2.5 hover:bg-ink hover:text-white hover:border-ink transition"
                    x-text="copied ? 'Copied ✓' : 'Copy link'">Copy link</button>
        </div>
    </div>
</article>

{{-- ============ PREV / NEXT ============ --}}
@if($newer || $older)
<section class="max-w-5xl mx-auto px-6 pb-20 grid sm:grid-cols-2 gap-4">
    @if($older)
        <a href="{{ route('blog.show', $older->slug) }}" class="group rounded-3xl border border-black/10 p-6 hover:border-plum transition">
            <p class="text-[11px] uppercase tracking-[0.2em] text-muted mb-2">← Older</p>
            <p class="font-serif text-xl group-hover:text-plum transition">{{ $older->title }}</p>
        </a>
    @else <span></span> @endif
    @if($newer)
        <a href="{{ route('blog.show', $newer->slug) }}" class="group rounded-3xl border border-black/10 p-6 text-right hover:border-plum transition">
            <p class="text-[11px] uppercase tracking-[0.2em] text-muted mb-2">Newer →</p>
            <p class="font-serif text-xl group-hover:text-plum transition">{{ $newer->title }}</p>
        </a>
    @endif
</section>
@endif

{{-- ============ RELATED ============ --}}
@if($related->count())
<section class="bg-ink text-cream">
    <div class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex items-end justify-between mb-10">
            <h2 class="font-serif text-4xl md:text-5xl">Keep <span class="italic text-rose">reading.</span></h2>
            <a href="{{ route('blogs') }}" class="text-xs uppercase tracking-[0.2em] border-b border-cream/40 pb-1 hover:text-rose hover:border-rose transition">All stories</a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($related as $p)
                <a href="{{ route('blog.show', $p->slug) }}" class="group block">
                    <div class="aspect-[4/3] rounded-3xl overflow-hidden bg-cream/10 mb-4">
                        <img src="{{ $img($p, 800, 600) }}" alt="{{ $p->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy">
                    </div>
                    <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-2">{{ $p->category }}</p>
                    <h3 class="font-serif text-xl leading-snug group-hover:text-rose transition">{{ $p->title }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ CTA ============ --}}
<section class="max-w-7xl mx-auto px-6 py-20 text-center">
    <h2 class="font-serif text-4xl md:text-6xl mb-6">Ready to <span class="italic text-plum">wear the story?</span></h2>
    <a href="{{ route('shop') }}" class="inline-block bg-plum text-white text-xs rounded-full px-8 py-4 hover:bg-ink transition">Shop the collection</a>
</section>
@endsection