@extends('layouts.app')
@section('title', $product->name . ' — G. Collection')

@section('content')
@php
    $img   = $product->display_image ?? $product->image ?? ('https://placehold.co/900x1200/F8DCE8/5B1A4A?text=' . urlencode($product->name));
    $price = $product->display_price ?? $product->price;
    $c     = $product->category ?? null;
    $cname = is_object($c) ? ($c->name ?? null) : (is_string($c) ? $c : null);
    $cslug = is_object($c) ? ($c->slug ?? null) : (is_string($c) ? \Illuminate\Support\Str::slug($c) : null);
    $desc  = trim((string) ($product->description ?? ''));
    $wa    = 'https://wa.me/923347779231?text=' . urlencode("Hi G. Collection! I'm interested in: {$product->name} (Rs. " . number_format($price) . ") " . url()->current());
@endphp

<div x-data="{ q: 1, zoom: false, copied: false }" @keydown.escape.window="zoom=false">

    {{-- breadcrumb --}}
    <div class="max-w-7xl mx-auto px-5 md:px-6 pt-6 md:pt-8">
        <nav class="text-[11px] uppercase tracking-[0.2em] text-muted flex items-center gap-2 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-plum">Home</a><span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-plum">Shop</a>
            @if($cname)<span>/</span><a href="{{ route('shop') }}{{ $cslug ? '?category=' . $cslug : '' }}" class="hover:text-plum">{{ $cname }}</a>@endif
        </nav>
    </div>

    {{-- ============ PRODUCT ============ --}}
    <section class="max-w-7xl mx-auto px-5 md:px-6 py-6 md:py-10 grid md:grid-cols-2 gap-8 lg:gap-16 pb-28 md:pb-10">

        {{-- image --}}
        <div class="md:sticky md:top-24 self-start">
            <button type="button" @click="zoom=true" class="group relative block w-full aspect-[4/5] overflow-hidden rounded-[28px] md:rounded-[36px] bg-blush cursor-zoom-in">
                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                <span class="absolute bottom-4 right-4 bg-white/90 backdrop-blur text-[11px] uppercase tracking-widest rounded-full px-3 py-1.5 opacity-0 group-hover:opacity-100 transition hidden md:inline">Click to zoom</span>
            </button>
        </div>

        {{-- info --}}
        <div class="md:py-4">
            @if($cname)
                <p class="inline-block bg-blush text-plum text-[11px] uppercase tracking-[0.2em] rounded-full px-4 py-1.5 mb-5">{{ $cname }}</p>
            @endif
            <h1 class="font-serif text-4xl md:text-6xl leading-[1.04] mb-5">{{ $product->name }}</h1>
            <p class="text-2xl md:text-3xl font-medium text-plum mb-1">Rs. {{ number_format($price) }}</p>
            <p class="text-xs text-muted mb-8">Free shipping on orders of Rs. 10,000 and above</p>

            <div class="text-ink/75 leading-[1.8] text-[15px] mb-8">
                @if($desc !== '') {!! nl2br(e($desc)) !!} @else No description available. @endif
            </div>

            {{-- add to cart --}}
            <form id="add-form" action="{{ route('cart.add') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <p class="text-[11px] uppercase tracking-[0.2em] text-muted mb-3">Quantity</p>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="inline-flex items-center bg-white border border-black/10 rounded-full">
                        <button type="button" @click="q = Math.max(1, q - 1)" class="w-11 h-11 text-lg hover:text-plum" aria-label="Decrease">−</button>
                        <input type="number" name="quantity" x-model.number="q" min="1" class="w-12 text-center bg-transparent text-sm focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                        <button type="button" @click="q++" class="w-11 h-11 text-lg hover:text-plum" aria-label="Increase">+</button>
                    </div>
                    <button class="flex-1 min-w-[180px] bg-plum text-white rounded-full py-3.5 text-sm hover:bg-ink active:scale-[.99] transition inline-flex items-center justify-center gap-3 group">
                        Add to cart
                        <span class="group-hover:translate-x-1 transition">→</span>
                    </button>
                </div>
            </form>

            <a href="{{ $wa }}" target="_blank" rel="noopener"
               class="flex items-center justify-center gap-2 w-full border border-rose text-rose rounded-full py-3.5 text-sm hover:bg-rose hover:text-white transition mb-8">
                Order or ask on WhatsApp
            </a>

            {{-- trust --}}
            <div class="grid grid-cols-3 gap-3 mb-8 text-center">
                @foreach([['🚚','Free shipping','Over Rs. 10,000'],['💵','Pay your way','COD · Bank transfer'],['🔁','Easy exchange','Within 48 hours']] as [$i,$t,$d])
                    <div class="rounded-2xl bg-white border border-black/5 py-4 px-2">
                        <p class="text-xl mb-1">{{ $i }}</p>
                        <p class="text-[11px] font-medium leading-tight">{{ $t }}</p>
                        <p class="text-[10px] text-muted mt-0.5 leading-tight">{{ $d }}</p>
                    </div>
                @endforeach
            </div>

            {{-- accordion --}}
            <div x-data="{ o: 0 }" class="border-t border-black/10">
                @foreach([
                    ['Shipping & delivery','Free shipping on orders of Rs. 10,000 and above. Below that, standard delivery charges apply. We deliver across Pakistan from Karachi.'],
                    ['Returns & exchanges','Please contact us on WhatsApp within 48 hours of delivery to discuss returns or exchanges.'],
                    ['Care','Wash gently in cool water, turn embroidered pieces inside out, dry in the shade and iron on the reverse.'],
                ] as $n => [$t,$d])
                    <div class="border-b border-black/10">
                        <button type="button" @click="o = (o === {{ $n + 1 }} ? 0 : {{ $n + 1 }})" class="w-full flex items-center justify-between py-4 text-left">
                            <span class="font-serif text-lg">{{ $t }}</span>
                            <span class="text-plum text-xl transition-transform" :class="o === {{ $n + 1 }} ? 'rotate-45' : ''">+</span>
                        </button>
                        <p x-cloak x-show="o === {{ $n + 1 }}" class="text-sm text-muted leading-relaxed pb-4">{{ $d }}</p>
                    </div>
                @endforeach
            </div>

            {{-- share --}}
            <div class="flex items-center gap-3 mt-6 text-xs text-muted">
                <span>Share</span>
                <a href="https://wa.me/?text={{ urlencode($product->name . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="border border-black/15 rounded-full px-4 py-2 hover:bg-ink hover:text-white hover:border-ink transition">WhatsApp</a>
                <button type="button" @click="navigator.clipboard.writeText(@js(url()->current())); copied=true; setTimeout(()=>copied=false,1800)"
                        class="border border-black/15 rounded-full px-4 py-2 hover:bg-ink hover:text-white hover:border-ink transition" x-text="copied ? 'Copied ✓' : 'Copy link'">Copy link</button>
            </div>
        </div>
    </section>

    {{-- ============ MOBILE STICKY BAR ============ --}}
    <div class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-cream/95 backdrop-blur border-t border-black/10 px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] flex items-center gap-4">
        <div class="min-w-0">
            <p class="text-[10px] uppercase tracking-widest text-muted">Total</p>
            <p class="font-medium leading-tight" x-text="'Rs. ' + ({{ (int) $price }} * q).toLocaleString()">Rs. {{ number_format($price) }}</p>
        </div>
        <button type="submit" form="add-form" class="flex-1 bg-plum text-white rounded-full py-3.5 text-sm active:scale-[.99] transition">Add to cart</button>
    </div>

    {{-- ============ ZOOM LIGHTBOX ============ --}}
    <div x-cloak x-show="zoom" x-transition.opacity class="fixed inset-0 z-[60] bg-ink/90 backdrop-blur flex items-center justify-center p-4" @click="zoom=false">
        <button type="button" class="absolute top-5 right-5 w-11 h-11 rounded-full bg-white text-ink text-xl" @click="zoom=false" aria-label="Close">×</button>
        <img src="{{ $img }}" alt="{{ $product->name }}" class="max-h-[92vh] max-w-full rounded-2xl object-contain" @click.stop>
    </div>
</div>

{{-- ============ RELATED ============ --}}
@if($related->count())
<section class="bg-blush">
    <div class="max-w-7xl mx-auto px-5 md:px-6 py-16 md:py-20">
        <div class="flex items-end justify-between mb-8 md:mb-10">
            <h2 class="font-serif text-4xl md:text-6xl leading-[1.02]">You may also <span class="italic text-plum">like.</span></h2>
            <a href="{{ route('shop') }}" class="hidden sm:inline-block text-xs uppercase tracking-[0.2em] border-b border-ink pb-1 hover:text-plum hover:border-plum transition">View all</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-3 sm:gap-x-5 md:gap-x-6 gap-y-9">
            @foreach($related as $p)
                @include('partials.product-card', ['p' => $p])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection