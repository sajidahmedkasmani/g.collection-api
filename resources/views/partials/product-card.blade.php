@php
    $img   = $p->display_image ?? $p->image ?? ('https://placehold.co/600x800/F8DCE8/5B1A4A?text=' . urlencode($p->name));
    $price = $p->display_price ?? $p->price;
    $cname = $p->productCategory?->name ?? ($p->category ?? null);
    $isNew = $p->created_at && $p->created_at->gt(now()->subDays(14));
    $isWishlisted = in_array($p->id, array_map('intval', session('wishlist', [])), true);
@endphp

<div class="group">
    {{-- image area: link + quick-add are siblings (no nested interactive elements) --}}
    <div class="relative aspect-[3/4] overflow-hidden rounded-2xl md:rounded-3xl bg-blush">
        <a href="{{ route('product.details', $p->slug) }}" class="absolute inset-0 block" aria-label="{{ $p->name }}">
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
        <button type="button" data-wishlist-toggle data-product-id="{{ $p->id }}" aria-pressed="{{ $isWishlisted ? 'true' : 'false' }}"
                aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}"
                class="absolute right-3 top-3 z-10 w-10 h-10 rounded-full bg-white/95 shadow flex items-center justify-center transition hover:text-rose {{ $isWishlisted ? 'text-rose' : 'text-ink/70' }}">
            <svg class="w-5 h-5" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M12 21s-7-4.6-9.3-9A5.3 5.3 0 0 1 12 6a5.3 5.3 0 0 1 9.3 6C19 16.4 12 21 12 21Z"/></svg>
        </button>
    </div>

    <a href="{{ route('product.details', $p->slug) }}" data-product-card class="block pt-3">
        <h3 class="text-[13px] md:text-sm leading-snug line-clamp-2 group-hover:text-plum transition">{{ $p->name }}</h3>
        <p class="text-sm font-medium mt-1">Rs. {{ number_format($price) }}</p>
    </a>
</div>