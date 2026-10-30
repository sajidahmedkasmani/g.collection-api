@extends('layouts.app')
@section('title', 'G. Collection — Wear the Story, Every Stitch')

@section('content')
@php
    $list = $products->values();
    $first = $list->get(0);
    $p1 = $list->get(1) ?? $first;
    $p2 = $list->get(2) ?? $first;
    $p3 = $list->get(3) ?? $first;
    $slideProducts = $list->take(3);

    $ph = function($p, $w = 600, $h = 800) {
        if (!$p) return 'https://placehold.co/'.$w.'x'.$h.'/F8DCE8/5B1A4A?text=Product';
        if (!empty($p->display_image)) return $p->display_image;
        return 'https://placehold.co/'.$w.'x'.$h.'/F8DCE8/5B1A4A?text='.urlencode($p->name ?? 'Product');
    };

    $cat = fn($p) => $p->category ?? '';
@endphp




{{-- ============ DYNAMIC HERO SLIDER ============ --}}

{{-- Inter font (Aesop-style sans-serif) — agar layout ke <head> mein pehle se nahi hai to yeh line wahan rakhein --}}
{{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap" rel="stylesheet"> --}}

@if(isset($sliders) && $sliders->count())
<section x-data="{
    i: 0,
    n: {{ $sliders->count() }},
    t: null,
    durations: [{{ $sliders->map(fn($s) => $s->slide_duration ?? 5500)->join(',') }}],
    go(k) { this.i = (k + this.n) % this.n; this.reset(); },
    reset() {
        clearInterval(this.t);
        const d = this.durations[this.i] || 5500;
        this.t = setInterval(() => this.i = (this.i + 1) % this.n, d);
    }
}" x-init="reset()" class="relative overflow-hidden min-h-[560px] md:min-h-[640px]">

    @foreach($sliders as $k => $slide)
        <div x-show="i === {{ $k }}"
             x-transition.opacity.duration.700ms
             @if($k > 0) x-cloak @endif
             class="absolute inset-0 w-full h-full"
             style="{{ $slide->background_style }}">

            @if($slide->content_type === 'image_only')
                {{-- IMAGE ONLY MODE --}}
                <a href="{{ $slide->button_url ?: '#' }}" class="block w-full h-full relative">
                    @if($slide->image_url)
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?? 'Banner' }}"
                             class="absolute inset-0 w-full h-full object-cover"
                             loading="{{ $k === 0 ? 'eager' : 'lazy' }}">
                    @endif
                </a>
            @else
                {{-- CUSTOM MODE --}}
                <div class="max-w-7xl mx-auto px-6 pt-14 pb-20 h-full grid md:grid-cols-2 gap-10 items-center
                            {{ $slide->image_position === 'left' ? 'md:[&>*:first-child]:order-2' : '' }}">

                    <div style="color: {{ $slide->text_color ?? '#1A1A1A' }}; text-align: {{ $slide->text_alignment ?? 'left' }}">
                        @if($slide->subtitle)
                            <p class="text-xs uppercase tracking-[0.2em] opacity-70 mb-4">{{ $slide->subtitle }}</p>
                        @endif
                        @if($slide->title)
                            <h1 class="text-5xl md:text-7xl leading-[1.02] font-normal mb-6"
                                style="font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;">
                                {!! nl2br(e($slide->title)) !!}
                            </h1>
                        @endif
                        @if($slide->description)
                            <p class="text-sm md:text-base opacity-80 max-w-sm leading-relaxed mb-8">{{ $slide->description }}</p>
                        @endif
                        <div class="flex flex-wrap gap-3 {{ ($slide->text_alignment ?? 'left') === 'center' ? 'justify-center' : '' }}">
                            @if($slide->button_text)
                                <a href="{{ $slide->button_url ?: '#' }}"
                                   class="inline-block bg-black text-white text-xs font-medium rounded-full px-6 py-3 hover:opacity-80 transition">
                                    {{ $slide->button_text }}
                                </a>
                            @endif
                            @if($slide->secondary_button_text)
                                <a href="{{ $slide->secondary_button_url ?: '#' }}"
                                   class="inline-block border border-current text-xs rounded-full px-6 py-3 hover:opacity-70 transition">
                                    {{ $slide->secondary_button_text }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="relative flex justify-center md:justify-end items-end gap-4 h-full">
                        @if($slide->image_url)
                            <a href="{{ $slide->button_url ?: '#' }}"
                               class="block w-56 md:w-72 aspect-[3/4] overflow-hidden rounded-t-full shadow-lg self-end">
                                <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}"
                                     class="w-full h-full object-cover" loading="{{ $k === 0 ? 'eager' : 'lazy' }}">
                            </a>
                        @endif
                        @if($slide->secondary_image_url)
                            <div class="hidden md:block w-28 aspect-[3/4] overflow-hidden rounded-t-full shadow self-end">
                                <img src="{{ $slide->secondary_image_url }}" alt=""
                                     class="w-full h-full object-cover" loading="lazy">
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    {{-- Navigation --}}
    @if($sliders->count() > 1)
        <div class="absolute right-6 bottom-6 flex items-center gap-2 z-10">
            <button @click="go(i-1)" class="w-9 h-9 rounded-full bg-white/80 hover:bg-white shadow flex items-center justify-center" aria-label="Previous">‹</button>
            @foreach($sliders as $k => $_)
                <button @click="go({{ $k }})"
                        :class="i === {{ $k }} ? 'w-6 bg-black' : 'w-2 bg-black/30'"
                        class="h-2 rounded-full transition-all" aria-label="Slide {{ $k+1 }}"></button>
            @endforeach
            <button @click="go(i+1)" class="w-9 h-9 rounded-full bg-white/80 hover:bg-white shadow flex items-center justify-center" aria-label="Next">›</button>
        </div>
    @endif
</section>
@else
    {{-- Fallback hero (no sliders) --}}
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-10">
        <p class="text-xs uppercase tracking-widest text-muted mb-6">Stitched · Unstitched · Karachi</p>
        <h1 class="text-5xl md:text-7xl leading-[1.05] font-normal mb-8 max-w-5xl"
            style="font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;">
            Wear the Story,<br>
            <span class="italic opacity-70">Live the Craft.</span>
        </h1>
        <p class="text-sm md:text-base text-muted max-w-xl leading-relaxed mb-10">
            Discover our latest collection of hand-crafted ensembles — thoughtfully designed
            for the modern wardrobe. From everyday essentials to statement pieces,
            each garment tells a story of heritage and craftsmanship.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ url('/shop') }}"
               class="inline-block bg-black text-white text-xs font-medium rounded-full px-6 py-3 hover:opacity-80 transition">
                Shop Collection
            </a>
            <a href="{{ url('/about') }}"
               class="inline-block border border-current text-xs rounded-full px-6 py-3 hover:opacity-70 transition">
                Our Story
            </a>
        </div>
    </section>
@endif



{{-- ============ COLLECTIONS ============ --}}
<section class="max-w-7xl mx-auto px-6 py-24">
    <h2 class="font-serif text-5xl md:text-7xl leading-[1.02] mb-14">Three ways<br>to dress the day.</h2>
    <p class="md:ml-auto md:max-w-[200px] text-xs text-muted -mt-24 mb-16 hidden md:block">Stitched, unstitched and everything that goes with it. Pick a collection to start.</p>

    <div class="grid md:grid-cols-3 gap-5 items-end">
        @foreach([
            ['Stitched','stitched','h-[420px]', $p1],
            ['Unstitched','unstitched','h-[360px] md:mb-0', $p2],
            ['Accessories','accessories','h-[420px]', $p3],
        ] as [$title,$slug,$h,$prod])
            <a href="{{ route('shop') }}?category={{ $slug }}" class="group relative block {{ $h }} overflow-hidden rounded-t-[999px] bg-blush">
                <img src="{{ $ph($prod) }}" alt="{{ $title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-5 left-6 right-5 flex items-end justify-between text-white">
                    <p class="font-serif text-3xl">{{ $title }}</p>
                    <span class="w-9 h-9 rounded-full bg-white text-ink flex items-center justify-center group-hover:bg-rose group-hover:text-white transition">→</span>
                </div>
            </a>
        @endforeach
    </div>
</section>

{{-- ============ MOST LOVED ============ --}}
@if($list->count() && $first)
<section class="max-w-7xl mx-auto px-6 pb-24">
    <div class="grid md:grid-cols-2 gap-10">
        <a href="{{ route('product.show', $first->slug) }}" class="group block">
            <div class="aspect-[4/5] bg-blush overflow-hidden mb-4 relative">
                <span class="absolute top-4 left-4 bg-white text-[10px] uppercase tracking-widest px-2 py-1 z-10">{{ $cat($first) ?: 'New' }}</span>
                <img src="{{ $ph($first) }}" alt="{{ $first->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy">
            </div>
            <div class="flex justify-between gap-4">
                <h3 class="font-serif text-xl">{{ $first->name }}</h3>
                <p class="text-sm text-muted whitespace-nowrap">Rs. {{ number_format($first->display_price) }}</p>
            </div>
        </a>

        <div>
            <div class="flex items-start justify-between mb-8">
                <h2 class="font-serif text-5xl md:text-6xl leading-[1.02]">Most loved,<br>right now.</h2>
                <a href="{{ route('shop') }}" class="text-xs underline underline-offset-4 hover:text-rose">See all</a>
            </div>
            <div class="grid grid-cols-2 gap-5">
                @foreach($list->slice(1,2) as $idx => $p)
                    <a href="{{ route('product.show', $p->slug) }}" class="group block {{ $idx==2 ? 'mt-10' : '' }}">
                        <div class="aspect-[3/4] bg-blush overflow-hidden mb-3 rounded-t-[999px]">
                            <img src="{{ $ph($p) }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy">
                        </div>
                        <h3 class="text-xs mb-1 group-hover:text-rose transition">{{ $p->name }}</h3>
                        <p class="text-xs text-muted">Rs. {{ number_format($p->display_price) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============ SHOP FROM THE COLLECTION (carousel) ============ --}}
<section class="max-w-7xl mx-auto px-6 pb-24" x-data="{ scroll(d){ $refs.row.scrollBy({left:d*320,behavior:'smooth'}) } }">
    <div class="flex items-center justify-between mb-5">
        <p class="text-[11px] uppercase tracking-[0.2em] text-muted">Shop from the collection · Swipe or click</p>
        <div class="flex gap-2">
            <button @click="scroll(-1)" class="w-8 h-8 rounded-full border border-black/20 hover:bg-ink hover:text-white transition" aria-label="Previous">‹</button>
            <button @click="scroll(1)" class="w-8 h-8 rounded-full border border-black/20 hover:bg-ink hover:text-white transition" aria-label="Next">›</button>
        </div>
    </div>
    <div x-ref="row" class="flex gap-5 overflow-x-auto no-scrollbar snap-x snap-mandatory">
        @foreach($list as $p)
            <a href="{{ route('product.show', $p->slug) }}" class="group snap-start shrink-0 w-56 md:w-64">
                <div class="aspect-[3/4] bg-blush overflow-hidden mb-3 relative">
                    <span class="absolute top-3 left-3 bg-white text-[10px] uppercase tracking-widest px-2 py-1 z-10">{{ $cat($p) ?: 'New' }}</span>
                    <img src="{{ $ph($p) }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy">
                </div>
                <h3 class="text-xs mb-1 line-clamp-2">{{ $p->name }}</h3>
                <p class="text-xs text-muted">Rs. {{ number_format($p->display_price) }}</p>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ============ BIG MARQUEE ============ --}}
<div class="overflow-hidden border-y border-black/10 py-6">
    <div class="marquee marquee-slow whitespace-nowrap font-serif italic text-6xl md:text-8xl">
        @for($m=0;$m<4;$m++)
            <span class="mx-6">Embroidery</span><span class="text-rose">·</span>
            <span class="mx-6">Lace</span><span class="text-rose">·</span>
            <span class="mx-6">Lawn</span><span class="text-rose">·</span>
            <span class="mx-6">Cutwork</span><span class="text-rose">·</span>
        @endfor
    </div>
</div>

{{-- ============ PLUM FEATURE ============ --}}
@if($list->count() && $first)
<section class="bg-plum text-white relative overflow-hidden">
    <p class="absolute -top-6 left-0 font-serif italic text-[220px] leading-none text-white/10 select-none pointer-events-none whitespace-nowrap">Lace. Fringe.</p>
    <div class="max-w-7xl mx-auto px-6 py-28 relative">
        <div class="grid md:grid-cols-2 gap-14 items-center mb-24">
            <div class="flex justify-center">
                <div class="w-64 md:w-80 aspect-[3/5] overflow-hidden rounded-t-full bg-white/10">
                    <img src="{{ $ph($first) }}" alt="" class="w-full h-full object-cover" loading="lazy">
                </div>
            </div>
            <div>
                <h2 class="font-serif text-5xl md:text-7xl leading-[1.02] mb-6">Lace.<br><span class="italic text-rose">Fringe.</span><br>Cutwork.</h2>
                <p class="text-sm text-white/70 max-w-sm mb-8">Three details, one silhouette. Each piece is finished by hand in our Karachi workshop.</p>
                <a href="{{ route('product.show', $first->slug) }}" class="inline-flex items-center gap-2 bg-white text-plum text-xs rounded-full px-6 py-3 hover:bg-blush transition">Add to bag</a>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-14 items-center">
            <div class="order-2 md:order-1">
                <h2 class="font-serif text-5xl md:text-7xl leading-[1.02] mb-6">Lace.<br><span class="italic text-rose">Fringe.</span><br>Cutwork.</h2>
                <p class="text-sm text-white/70 max-w-sm mb-8">These details make these pieces fit the collection, wear after wear.</p>
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 border border-white/40 text-xs rounded-full px-6 py-3 hover:bg-white/10 transition">Shop the edit</a>
            </div>
            <div class="order-1 md:order-2 flex justify-center gap-4 items-end">
                <div class="w-48 md:w-64 aspect-[3/4] overflow-hidden bg-white/10"><img src="{{ $ph($p1) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
                <div class="w-20 md:w-24 aspect-[3/4] overflow-hidden bg-white/10"><img src="{{ $ph($p2) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
            </div>
        </div>
    </div>
</section>

{{-- ============ THE EDIT (pink) ============ --}}
<section class="bg-blush">
    <div class="max-w-7xl mx-auto px-6 py-24">
        <div class="flex justify-between text-[11px] uppercase tracking-[0.2em] text-plum/70 mb-10">
            <span>The Edit</span><span>Embroidery · Lace · Lawn</span><span>Karachi</span>
        </div>
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="font-serif text-6xl md:text-8xl leading-[0.98] text-ink mb-6">Made<br><span class="italic">by hand,</span><br>worn by<br>you.</h2>
                <p class="text-sm text-ink/70 max-w-xs mb-6 leading-relaxed">Soft lace sleeves, fringed hems, scalloped cutwork borders. The details that make a hand-finished kurta.</p>
                <a href="{{ route('shop') }}" class="inline-block bg-ink text-white text-xs rounded-full px-6 py-3 hover:bg-plum transition">Shop the edit</a>
            </div>
            @if($p1)
            <a href="{{ route('product.show', $p1->slug) }}" class="block">
                <div class="aspect-[4/5] overflow-hidden rounded-t-[999px] bg-white/40"><img src="{{ $ph($p1) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
                <div class="flex justify-between text-xs mt-3 text-ink/70"><span>{{ $p1->name }}</span><span>Rs. {{ number_format($p1->display_price) }}</span></div>
            </a>
            @endif
        </div>
        <div class="grid md:grid-cols-2 gap-12 mt-16">
            @if($p2)
            <a href="{{ route('product.show', $p2->slug) }}" class="block">
                <div class="aspect-[4/5] overflow-hidden bg-white/40"><img src="{{ $ph($p2) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
                <div class="flex justify-between text-xs mt-3 text-ink/70"><span>{{ $p2->name }}</span><span>Rs. {{ number_format($p2->display_price) }}</span></div>
            </a>
            @endif
            @if($p3)
            <a href="{{ route('product.show', $p3->slug) }}" class="block md:mt-24">
                <div class="aspect-[4/5] overflow-hidden rounded-t-[999px] bg-white/40"><img src="{{ $ph($p3) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
                <div class="flex justify-between text-xs mt-3 text-ink/70"><span>{{ $p3->name }}</span><span>Rs. {{ number_format($p3->display_price) }}</span></div>
            </a>
            @endif
        </div>
    </div>
</section>

{{-- ============ FIND YOUR PIECE ============ --}}
<section class="bg-ink text-cream" x-data="{ f:'all', hover:0 }">
    <div class="max-w-7xl mx-auto px-6 py-24">
        <h2 class="font-serif text-5xl md:text-7xl mb-8">Find your piece.</h2>
        <div class="flex flex-wrap gap-2 mb-10">
            @foreach(['all'=>'All','stitched'=>'Stitched','unstitched'=>'Unstitched','accessories'=>'Accessories'] as $k=>$label)
                <button @click="f='{{ $k }}'" :class="f==='{{ $k }}' ? 'bg-rose text-white' : 'border border-cream/30 text-cream/80'" class="text-xs rounded-full px-4 py-1.5 transition">{{ $label }}</button>
            @endforeach
        </div>
        <div class="grid md:grid-cols-2 gap-12">
            <ul>
                @foreach($list->take(9) as $idx => $p)
                    <li x-show="f==='all' || f==='{{ $cat($p) }}'" @mouseenter="hover={{ $idx }}"
                        class="flex items-center justify-between gap-4 border-b border-cream/15 py-4">
                        <a href="{{ route('product.show', $p->slug) }}" class="min-w-0">
                            <p class="font-serif text-xl truncate hover:text-rose transition">{{ $p->name }}</p>
                            <p class="text-[11px] text-cream/50 uppercase tracking-widest mt-1">{{ $cat($p) ?: 'Collection' }} · Rs. {{ number_format($p->display_price) }}</p>
                        </a>
                        <a href="{{ route('product.show', $p->slug) }}" class="shrink-0 bg-white text-ink text-[11px] rounded-full px-3 py-1 hover:bg-rose hover:text-white transition">+ Add</a>
                    </li>
                @endforeach
            </ul>
            <div class="hidden md:block relative aspect-[4/5] overflow-hidden rounded-t-[999px] bg-cream/10 self-start sticky top-28">
                @foreach($list->take(9) as $idx => $p)
                    <img x-show="hover==={{ $idx }}" x-transition.opacity src="{{ $ph($p) }}" alt="{{ $p->name }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============ GOOD TO KNOW ============ --}}
<section class="bg-ink text-cream border-t border-cream/10">
    <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12">
        <div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Good to know</p>
            <h2 class="font-serif text-5xl md:text-6xl mb-6">Shopping with<br>G. Collection</h2>
            <p class="text-sm text-cream/60 max-w-sm leading-relaxed">A full collection of stitched and unstitched ladies' clothing, based in Karachi, Pakistan. Our team is available Mon–Sat 11:00 AM – 9:00 PM.</p>
        </div>
        <div class="grid grid-cols-2 gap-x-8 gap-y-10 content-start">
            @foreach([
                ['Free shipping','On bills of Rs. 10,000 and above'],
                ['Pay your way','Cash on Delivery · Bank Transfer'],
                ['Talk to us','Mon–Sat 11:00 AM – 9:00 PM'],
                ['Based in','Karachi, Pakistan'],
            ] as [$t,$d])
                <div class="border-t border-cream/20 pt-4">
                    <p class="font-serif text-xl mb-1">{{ $t }}</p>
                    <p class="text-xs text-cream/60">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ FAQ ============ --}}
<section id="faq" class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-[320px_1fr] gap-12">
    <div>
        <h2 class="font-serif italic text-6xl text-plum mb-4">answered.</h2>
        <p class="text-xs text-muted mb-6 max-w-[240px]">Can't find what you need? Talk to us on WhatsApp.</p>
        <ul class="text-xs space-y-2">
            @foreach(['All questions','Ordering & payments','Shipping & delivery','Sizes & products','Returns & exchanges','Customer support'] as $k => $c)
                <li class="px-3 py-2 rounded {{ $k===0 ? 'bg-plum text-white' : 'bg-black/5' }}">{{ $c }}</li>
            @endforeach
        </ul>
        <div class="mt-8 text-xs text-muted space-y-1">
            <p class="text-ink font-medium">Still wondering?</p>
            <p>Mon–Sat, 11:00 AM – 9:00 PM</p>
            <a href="https://wa.me/923347779231" class="text-plum underline block">WhatsApp +92 334 7779231</a>
            <a href="mailto:support@gdotcollection.com" class="hover:text-rose block">support@gdotcollection.com</a>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4 content-start" x-data="{ o:null }">
        @foreach([
            ['Ordering & payments','How do I pay?','Choose Cash on Delivery or Bank Transfer at checkout. Bank details appear on the confirmation page.'],
            ['Ordering & payments','How does ordering work?','Browse the collection, add items to your cart, and proceed to checkout.'],
            ['Ordering & payments','Can I change or cancel an order?','Contact us on WhatsApp as soon as possible and we will help before it ships.'],
            ['Shipping & delivery','How much is shipping?','Free shipping on orders above Rs. 10,000. Below that, standard delivery charges apply.'],
            ['Shipping & delivery','How long will delivery take?','Orders usually arrive within a few working days depending on your city.'],
            ['Shipping & delivery','Where do you deliver?','We deliver across Pakistan from our Karachi base.'],
            ['Sizes & products','What\'s the difference between stitched and unstitched?','Stitched is ready to wear. Unstitched is fabric you take to your own tailor.'],
            ['Sizes & products','Which sizes and colours are available?','Each product page lists the sizes and colours currently in stock.'],
            ['Sizes & products','What kind of pieces do you sell?','Embroidered, lace and lawn ladies\' clothing, stitched and unstitched, plus accessories.'],
            ['Returns & exchanges','Can I exchange an item?','Please contact us via WhatsApp within 48 hours of delivery to discuss exchanges.'],
            ['Returns & exchanges','How long do I have to return something?','Within 48 hours of delivery. Message us on WhatsApp to start.'],
            ['Customer support','When is the team available?','Mon–Sat, 11:00 AM – 9:00 PM.'],
            ['Customer support','How do I contact your team?','WhatsApp +92 334 7779231 or support@gdotcollection.com.'],
            ['Customer support','What happens to my details?','We only use them to process and deliver your order.'],
        ] as $n => [$cat_, $q, $a])
            <div class="bg-ink text-cream rounded-xl p-5 cursor-pointer self-start" @click="o = (o==={{ $n }} ? null : {{ $n }})">
                <p class="text-[10px] uppercase tracking-[0.2em] text-rose mb-2">{{ $cat_ }}</p>
                <div class="flex justify-between items-start gap-4">
                    <p class="font-serif text-lg leading-snug">{{ $q }}</p>
                    <span class="w-6 h-6 shrink-0 rounded-full border border-cream/30 flex items-center justify-center text-sm" x-text="o==={{ $n }} ? '−' : '+'">+</span>
                </div>
                <p x-cloak x-show="o==={{ $n }}" x-collapse class="text-xs text-cream/70 mt-3 leading-relaxed">{{ $a }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ============ NEWSLETTER ============ --}}
<section class="bg-blush">
    <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="font-serif text-5xl md:text-7xl leading-[1.02] mb-6">Be first<br>to see<br><span class="italic text-plum">what's new.</span></h2>
            <p class="text-sm text-ink/70 max-w-sm mb-8">New arrivals and news from G. Collection, in your inbox. Just your email, nothing else.</p>
            <form onsubmit="event.preventDefault(); alert('Thanks! (newsletter backend later)');">
                <label class="text-[11px] uppercase tracking-[0.2em] text-ink/60">Email</label>
                <div class="flex border-b border-ink">
                    <input type="email" required placeholder="you@example.com" class="flex-1 bg-transparent py-3 text-lg focus:outline-none placeholder:text-black/30">
                    <button class="bg-ink text-white text-xs rounded-full px-5 my-2 hover:bg-plum transition">Subscribe</button>
                </div>
            </form>
        </div>
        <div class="flex justify-center">
            <div class="w-64 md:w-80 aspect-[3/4] bg-plum rounded-t-full flex items-center justify-center">
                <span class="font-serif italic text-white text-[160px] leading-none">G<span class="text-rose">.</span></span>
            </div>
        </div>
    </div>
</section>

@endsection