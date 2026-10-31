@php
    $img   = $p->display_image ?? $p->image ?? ('https://placehold.co/600x800/F8DCE8/5B1A4A?text=' . urlencode($p->name));
    $price = $p->display_price ?? $p->price;
    $c     = $p->category ?? null;
    $cname = is_object($c) ? ($c->name ?? null) : (is_string($c) ? $c : null);
    $isNew = $p->created_at && $p->created_at->gt(now()->subDays(14));
@endphp

<div class="group">
    {{-- image area: link + quick-add are siblings (no nested interactive elements) --}}
    <div class="relative aspect-[3/4] overflow-hidden rounded-2xl md:rounded-3xl bg-blush">
        <a href="{{ route('product.show', $p->slug) }}" class="absolute inset-0 block" aria-label="{{ $p->name }}">
            <img src="{{ $img }}" alt="{{ $p->name }}" loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
            <span class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></span>
        </a>

        <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start pointer-events-none">
            @if($isNew)
                <span class="bg-rose text-white text-[10px] uppercase tracking-widest px-2.5 py-1 rounded-full">New</span>
            @endif
            @if($cname)
                <span class="bg-white/90 backdrop-blur text-ink text-[10px] uppercase tracking-widest px-2.5 py-1 rounded-full">{{ $cname }}</span>
            @endif
        </div>

        <form action="{{ route('cart.add') }}" method="POST" class="absolute right-3 bottom-3 z-10">
            @csrf
            <input type="hidden" name="product_id" value="{{ $p->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" aria-label="Add {{ $p->name }} to cart" title="Quick add"
                    class="w-10 h-10 rounded-full bg-white text-plum shadow-lg flex items-center justify-center text-xl leading-none hover:bg-plum hover:text-white active:scale-95 transition md:opacity-0 md:translate-y-2 md:group-hover:opacity-100 md:group-hover:translate-y-0">+</button>
        </form>
    </div>

    <a href="{{ route('product.show', $p->slug) }}" class="block pt-3">
        <h3 class="text-[13px] md:text-sm leading-snug line-clamp-2 group-hover:text-plum transition">{{ $p->name }}</h3>
        <p class="text-sm font-medium mt-1">Rs. {{ number_format($price) }}</p>
    </a>
</div>