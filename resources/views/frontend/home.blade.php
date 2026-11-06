@extends('frontend.layout.master')
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

    $cat = fn($p) => $p->productCategory?->name ?? ($p->category ?? '');
@endphp




{{-- ============ DYNAMIC HERO SLIDER ============ --}}

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
                <a href="{{ $slide->button_url ?: '#' }}" class="block w-full h-full relative">
                    @if($slide->image_url)
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?? 'Banner' }}"
                             class="absolute inset-0 w-full h-full object-cover"
                             loading="{{ $k === 0 ? 'eager' : 'lazy' }}">
                    @endif
                </a>
            @else
                <div class="max-w-7xl mx-auto px-6 pt-14 pb-20 h-full grid md:grid-cols-2 gap-10 items-center
                            {{ $slide->image_position === 'left' ? 'md:[&>*:first-child]:order-2' : '' }}">

                    <div style="color: {{ $slide->text_color ?? '#1A1A1A' }}; text-align: {{ $slide->text_alignment ?? 'left' }}">
                        @if($slide->subtitle)
                            <p class="text-xs uppercase tracking-[0.2em] opacity-70 mb-4">{{ $slide->subtitle }}</p>
                        @endif
                        @if($slide->title)
                            <h1 class="font-serif italic text-5xl md:text-7xl leading-[1.02] mb-6">
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

<!-- ============ COLLECTIONS ============ -->
    <section class="max-w-7xl mx-auto px-6 py-24">
        <h2 class="font-serif text-5xl md:text-7xl leading-[1.02] mb-6">Explore our collections</h2>
        <p class="text-sm text-muted mb-10">Browse clothing, bags and more by collection and category.</p>

        <div class="grid md:grid-cols-3 gap-5 items-start">
            @foreach($collections->take(3) as $index => $collection)
                @php
                    $fallbackProducts = [$p1, $p2, $p3];
                    $imageUrl = cms_image('collection_' . ($index + 1) . '_image', $ph($fallbackProducts[$index] ?? null));
                    $height = $index === 1 ? 'h-[360px]' : 'h-[420px]';
                @endphp
                <div>
                    <a href="{{ route('shop', ['collection' => $collection->slug]) }}" class="group relative block {{ $height }} overflow-hidden rounded-t-[999px] bg-blush">
                        <img src="{{ $imageUrl }}" alt="{{ $collection->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-5 left-6 right-5 flex items-end justify-between text-white">
                            <p class="font-serif text-3xl">{{ $collection->name }}</p>
                            <span class="w-9 h-9 rounded-full bg-white text-ink flex items-center justify-center group-hover:bg-rose group-hover:text-white transition">→</span>
                        </div>
                    </a>
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach($collection->categories as $category)
                            <a href="{{ route('shop', ['collection' => $collection->slug, 'category' => $category->slug]) }}"
                               class="text-xs border border-black/15 rounded-full px-3 py-1.5 hover:border-plum hover:text-plum">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

@if($list->count() && $first)

    <!-- ============ MOST LOVED ============ -->
        <section class="max-w-7xl mx-auto px-6 pb-24">
            <div class="grid md:grid-cols-2 gap-10">
                <a href="{{ route('product.details', $first->slug) }}" class="group block">
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
                        <h2 class="font-serif text-5xl md:text-6xl leading-[1.02]">{!! nl2br(e(setting('most_loved_title', "Most loved,\nright now."))) !!}</h2>
                        <a href="{{ setting('most_loved_link_url', route('shop')) }}" class="text-xs underline underline-offset-4 hover:text-rose">{{ setting('most_loved_link_text', 'See all') }}</a>
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        @foreach($list->slice(1,2) as $idx => $p)
                            <a href="{{ route('product.details', $p->slug) }}" class="group block {{ $idx==2 ? 'mt-10' : '' }}">
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

    <!-- ============ SHOP FROM THE COLLECTION ============ -->
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
                    <a href="{{ route('product.details', $p->slug) }}" class="group snap-start shrink-0 w-56 md:w-64">
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

<!-- ============ BIG MARQUEE ============ -->
    @php
        $marqueeText = setting('marquee_items', 'Embroidery · Lace · Lawn · Cutwork');
        $marqueeParts = array_filter(array_map('trim', explode('·', $marqueeText)));
        if (empty($marqueeParts)) $marqueeParts = ['Embroidery', 'Lace', 'Lawn', 'Cutwork'];
    @endphp
    <div class="overflow-hidden border-y border-black/10 py-6">
        <div class="marquee marquee-slow whitespace-nowrap font-serif italic text-6xl md:text-8xl">
            @for($m=0;$m<4;$m++)
                @foreach($marqueeParts as $part)
                    <span class="mx-6">{{ $part }}</span><span class="text-rose">·</span>
                @endforeach
            @endfor
        </div>
    </div>

<!-- ============ PLUM FEATURE ============ -->
    @if($list->count() && $first)
    <section class="bg-plum text-white relative overflow-hidden">
        <p class="absolute -top-6 left-0 font-serif italic text-[220px] leading-none text-white/10 select-none pointer-events-none whitespace-nowrap">{{ setting('plum_watermark', 'Lace. Fringe.') }}</p>
        <div class="max-w-7xl mx-auto px-6 py-28 relative">
            <div class="grid md:grid-cols-2 gap-14 items-center mb-24">
                <div class="flex justify-center">
                    <div class="w-64 md:w-80 aspect-[3/5] overflow-hidden rounded-t-full bg-white/10">
                        <img src="{{ cms_image('plum_b1_image', $ph($first)) }}" alt="" class="w-full h-full object-cover" loading="lazy">
                    </div>
                </div>
                <div>
                    <h2 class="font-serif text-5xl md:text-7xl leading-[1.02] mb-6">{!! setting('plum_b1_title', 'Lace.<br><span class="italic text-rose">Fringe.</span><br>Cutwork.') !!}</h2>
                    <p class="text-sm text-white/70 max-w-sm mb-8">{{ setting('plum_b1_desc', 'Three details, one silhouette. Each piece is finished by hand in our Karachi workshop.') }}</p>
                    <a href="{{ setting('plum_b1_btn_url', route('product.details', $first->slug)) }}" class="inline-flex items-center gap-2 bg-white text-plum text-xs rounded-full px-6 py-3 hover:bg-blush transition">{{ setting('plum_b1_btn_text', 'Add to bag') }}</a>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-14 items-center">
                <div class="order-2 md:order-1">
                    <h2 class="font-serif text-5xl md:text-7xl leading-[1.02] mb-6">{!! setting('plum_b2_title', 'Lace.<br><span class="italic text-rose">Fringe.</span><br>Cutwork.') !!}</h2>
                    <p class="text-sm text-white/70 max-w-sm mb-8">{{ setting('plum_b2_desc', 'These details make these pieces fit the collection, wear after wear.') }}</p>
                    <a href="{{ setting('plum_b2_btn_url', route('shop')) }}" class="inline-flex items-center gap-2 border border-white/40 text-xs rounded-full px-6 py-3 hover:bg-white/10 transition">{{ setting('plum_b2_btn_text', 'Shop the edit') }}</a>
                </div>
                <div class="order-1 md:order-2 flex justify-center gap-4 items-end">
                    <div class="w-48 md:w-64 aspect-[3/4] overflow-hidden bg-white/10"><img src="{{ cms_image('plum_b2_image_1', $ph($p1)) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
                    <div class="w-20 md:w-24 aspect-[3/4] overflow-hidden bg-white/10"><img src="{{ cms_image('plum_b2_image_2', $ph($p2)) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
                </div>
            </div>
        </div>
    </section>

<!-- ============ THE EDIT ============ -->
<section class="bg-blush">
    <div class="max-w-7xl mx-auto px-6 py-24">
        <div class="flex justify-between text-[11px] uppercase tracking-[0.2em] text-plum/70 mb-10">
            <span>{{ setting('edit_badge_1', 'The Edit') }}</span><span>{{ setting('edit_badge_2', 'Embroidery · Lace · Lawn') }}</span><span>{{ setting('edit_badge_3', 'Karachi') }}</span>
        </div>
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="font-serif text-6xl md:text-8xl leading-[0.98] text-ink mb-6">{!! setting('edit_title', 'Made<br><span class="italic">by hand,</span><br>worn by<br>you.') !!}</h2>
                <p class="text-sm text-ink/70 max-w-xs mb-6 leading-relaxed">{{ setting('edit_desc', 'Soft lace sleeves, fringed hems, scalloped cutwork borders. The details that make a hand-finished kurta.') }}</p>
                <a href="{{ setting('edit_btn_url', route('shop')) }}" class="inline-block bg-ink text-white text-xs rounded-full px-6 py-3 hover:bg-plum transition">{{ setting('edit_btn_text', 'Shop the edit') }}</a>
            </div>
            @if($p1)
            <a href="{{ route('product.details', $p1->slug) }}" class="block">
                <div class="aspect-[4/5] overflow-hidden rounded-t-[999px] bg-white/40"><img src="{{ $ph($p1) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
                <div class="flex justify-between text-xs mt-3 text-ink/70"><span>{{ $p1->name }}</span><span>Rs. {{ number_format($p1->display_price) }}</span></div>
            </a>
            @endif
        </div>
        <div class="grid md:grid-cols-2 gap-12 mt-16">
            @if($p2)
            <a href="{{ route('product.details', $p2->slug) }}" class="block">
                <div class="aspect-[4/5] overflow-hidden bg-white/40"><img src="{{ $ph($p2) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
                <div class="flex justify-between text-xs mt-3 text-ink/70"><span>{{ $p2->name }}</span><span>Rs. {{ number_format($p2->display_price) }}</span></div>
            </a>
            @endif
            @if($p3)
            <a href="{{ route('product.details', $p3->slug) }}" class="block md:mt-24">
                <div class="aspect-[4/5] overflow-hidden rounded-t-[999px] bg-white/40"><img src="{{ $ph($p3) }}" alt="" class="w-full h-full object-cover" loading="lazy"></div>
                <div class="flex justify-between text-xs mt-3 text-ink/70"><span>{{ $p3->name }}</span><span>Rs. {{ number_format($p3->display_price) }}</span></div>
            </a>
            @endif
        </div>
    </div>
</section>

<!-- ============ FIND YOUR PIECE (Mobile Optimized) ============ -->
<section class="bg-ink text-cream" x-data="{ f:'all', hover:0 }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 md:py-24">
        <h2 class="font-serif text-4xl sm:text-5xl md:text-7xl mb-6 md:mb-8">{{ setting('find_piece_title', 'Find your piece.') }}</h2>
        
        <div class="flex flex-wrap gap-2 mb-8 md:mb-10">
            @foreach(['all'=>'All','stitched'=>'Stitched','unstitched'=>'Unstitched','accessories'=>'Accessories'] as $k=>$label)
                <button @click="f='{{ $k }}'" :class="f==='{{ $k }}' ? 'bg-rose text-white' : 'border border-cream/30 text-cream/80'" class="text-xs rounded-full px-4 py-1.5 transition">{{ $label }}</button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-start">
            <ul class="w-full">
                @foreach($list->take(9) as $idx => $p)
                    <li x-show="f==='all' || f==='{{ $cat($p) }}'" @mouseenter="hover={{ $idx }}"
                        class="flex items-center justify-between gap-4 border-b border-cream/15 py-3.5 md:py-4">
                        <a href="{{ route('product.details', $p->slug) }}" class="min-w-0 flex-1">
                            <p class="font-serif text-base sm:text-xl truncate hover:text-rose transition">{{ $p->name }}</p>
                            <p class="text-[10px] sm:text-[11px] text-cream/50 uppercase tracking-widest mt-0.5 sm:mt-1">{{ $cat($p) ?: 'Collection' }} · Rs. {{ number_format($p->display_price) }}</p>
                        </a>
                        <a href="{{ route('product.details', $p->slug) }}" class="shrink-0 bg-white text-ink text-[11px] rounded-full px-3 py-1 hover:bg-rose hover:text-white transition">+ Add</a>
                    </li>
                @endforeach
            </ul>
            <div class="hidden md:block relative aspect-[4/5] overflow-hidden rounded-t-[999px] bg-cream/10 sticky top-28">
                @foreach($list->take(9) as $idx => $p)
                    <img x-show="hover==={{ $idx }}" x-transition.opacity src="{{ $ph($p) }}" alt="{{ $p->name }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- ============ GOOD TO KNOW ============ -->
    <section class="bg-cream text-black border-t border-cream/10">
        <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12">
            <div>
                <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">{{ setting('good_to_know_badge', 'Good to know') }}</p>
                <h2 class="font-serif text-5xl md:text-6xl mb-6">{!! setting('good_to_know_title', 'Shopping with<br>G. Collection') !!}</h2>
                <p class="text-sm text-ink/60 max-w-sm leading-relaxed">{{ setting('good_to_know_desc', "A full collection of stitched and unstitched ladies' clothing, based in Karachi, Pakistan. Our team is available Mon–Sat 11:00 AM – 9:00 PM.") }}</p>
            </div>
            <div class="grid grid-cols-2 gap-x-8 gap-y-10 content-start">
                @foreach([
                    [setting('good_to_know_1_title', 'Free shipping'), setting('good_to_know_1_desc', 'On bills of Rs. 10,000 and above')],
                    [setting('good_to_know_2_title', 'Pay your way'), setting('good_to_know_2_desc', 'Cash on Delivery · Bank Transfer')],
                    [setting('good_to_know_3_title', 'Talk to us'), setting('good_to_know_3_desc', 'Mon–Sat 11:00 AM – 9:00 PM')],
                    [setting('good_to_know_4_title', 'Based in'), setting('good_to_know_4_desc', 'Karachi, Pakistan')],
                ] as [$t,$d])
                    @if($t)
                        <div class="border-t border-ink/20 pt-4">
                            <p class="font-serif text-xl mb-1">{{ $t }}</p>
                            <p class="text-xs text-ink/60">{{ $d }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

<!-- ============ FAQ ============ -->
    @php
        $faqList = isset($faqs) ? $faqs : \App\Models\Faq::active()->get();
        $faqCategories = $faqList->pluck('category')->unique()->values();
    @endphp
    <section id="faq" class="bg-[#fbeef3]" x-data="{ activeCat: 'All questions', o: null }">
        <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-[320px_1fr] gap-12">
            <div>
                <h2 class="font-serif text-6xl mb-4">
                    {{ setting('faq_section_title', 'Questions, answered.') }}
                </h2>
                <p class="text-xs text-muted mb-6 max-w-[240px]">{{ setting('faq_section_subtitle', "Can't find what you need? Talk to us on WhatsApp.") }}</p>
                <ul class="text-xs space-y-2">
                    <li @click="activeCat = 'All questions'; o = null"
                        :class="activeCat === 'All questions' ? 'bg-plum text-white font-medium' : 'bg-black/5 text-ink hover:bg-black/10'"
                        class="px-3.5 py-2.5 rounded-xl cursor-pointer transition">
                        All questions ({{ $faqList->count() }})
                    </li>
                    @foreach($faqCategories as $catName)
                        <li @click="activeCat = '{{ addslashes($catName) }}'; o = null"
                            :class="activeCat === '{{ addslashes($catName) }}' ? 'bg-plum text-white font-medium' : 'bg-black/5 text-ink hover:bg-black/10'"
                            class="px-3.5 py-2.5 rounded-xl cursor-pointer transition">
                            {{ $catName }}
                        </li>
                    @endforeach
                </ul>
                <div class="mt-8 text-xs text-muted space-y-1">
                    <p class="text-ink font-medium">Still wondering?</p>
                    <p>{{ setting('faq_timing', 'Mon–Sat, 11:00 AM – 9:00 PM') }}</p>
                    <a href="{{ whatsapp_link() }}" target="_blank" rel="noopener" class="text-plum underline block font-medium">WhatsApp {{ setting('contact_phone', '+92 334 7779231') }}</a>
                    <a href="mailto:{{ setting('contact_email', 'support@gdotcollection.com') }}" class="hover:text-rose block">{{ setting('contact_email', 'support@gdotcollection.com') }}</a>
                </div>
            </div>
    
            <div class="grid md:grid-cols-2 gap-4 content-start">
                @forelse($faqList as $n => $faqItem)
                    <div x-show="activeCat === 'All questions' || activeCat === '{{ addslashes($faqItem->category) }}'"
                         class="bg-ink text-cream rounded-xl p-5 cursor-pointer self-start transition hover:ring-1 hover:ring-rose/40"
                         @click="o = (o === {{ $n }} ? null : {{ $n }})">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-rose mb-2">{{ $faqItem->category }}</p>
                        <div class="flex justify-between items-start gap-4">
                            <p class="font-serif text-lg leading-snug">{{ $faqItem->question }}</p>
                            <span class="w-6 h-6 shrink-0 rounded-full border border-cream/30 flex items-center justify-center text-sm" x-text="o === {{ $n }} ? '−' : '+'">+</span>
                        </div>
                        <p x-cloak x-show="o === {{ $n }}" x-collapse class="text-xs text-cream/70 mt-3 leading-relaxed">{{ $faqItem->answer }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No questions available right now.</p>
                @endforelse
            </div>
        </div>
    </section>

<!-- ============ NEWSLETTER ============ -->
    <section class="bg-blush">
        <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="font-serif text-5xl md:text-7xl leading-[1.02] mb-6">{!! setting('newsletter_title', "Be first<br>to see<br><span class=\"italic text-plum\">what's new.</span>") !!}</h2>
                <p class="text-sm text-ink/70 max-w-sm mb-8">{{ setting('newsletter_desc', 'New arrivals and news from G. Collection, in your inbox. Just your email, nothing else.') }}</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" data-newsletter-form>
                    @csrf
                    <label class="text-[11px] uppercase tracking-[0.2em] text-ink/60">Email</label>
                    <div class="flex border-b border-ink">
                        <input type="email" name="email" required autocomplete="email" placeholder="you@example.com" class="flex-1 bg-transparent py-3 text-lg focus:outline-none placeholder:text-black/30">
                        <button type="submit" class="bg-ink text-white text-xs rounded-full px-5 my-2 hover:bg-plum transition">Subscribe</button>
                    </div>
                    @error('email') <p class="text-xs text-red-700 mt-2">{{ $message }}</p> @enderror
                    <p class="text-[11px] text-ink/50 mt-2">Subscribe for product news. Unsubscribe any time.</p>
                </form>
            </div>
            <div class="flex justify-center">
                <div class="w-64 md:w-80 aspect-[3/4] bg-plum rounded-t-full flex items-center justify-center">
                    <span class="font-serif italic text-white text-[160px] leading-none">{{ setting('newsletter_monogram', 'G.') }}</span>
                </div>
            </div>
        </div>
    </section>

@endsection