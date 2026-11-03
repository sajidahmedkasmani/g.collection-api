<div class="flex items-center justify-between px-5 py-4 border-b border-black/10">
    <h2 class="font-serif text-2xl">Your cart <span class="text-muted text-base font-sans">({{ $summary['item_count'] }})</span></h2>
    <button type="button" data-close-cart aria-label="Close cart" class="p-1 -mr-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
    </button>
</div>

@if(empty($cart))
    <div class="flex-1 flex flex-col items-center justify-center text-center px-8">
        <div class="w-28 h-36 arch bg-blush flex items-end justify-center pb-8">
            <svg class="w-9 h-9 text-plum" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
        </div>
        <p class="font-serif text-2xl mt-6">Nothing here yet</p>
        <p class="text-sm text-muted mt-1">Pieces you add will wait for you here.</p>
        <a href="{{ route('shop') }}" class="mt-6 bg-plum text-cream px-7 py-3 rounded-full text-sm font-medium hover:bg-ink transition">Browse the collection</a>
    </div>
@else
    <div class="px-5 pt-4 pb-3 border-b border-black/10">
        <p class="text-sm">
            @if($summary['amount_until_free_shipping'] > 0)
                Add <strong>Rs. {{ number_format($summary['amount_until_free_shipping']) }}</strong> more for free shipping
            @else
                You've unlocked free shipping
            @endif
        </p>
        <div class="h-1.5 rounded-full bg-blush mt-2 overflow-hidden"><div class="h-full bg-rose rounded-full transition-all duration-300" style="width: {{ $summary['shipping_progress'] }}%"></div></div>
    </div>
    <ul class="flex-1 overflow-y-auto px-5 divide-y divide-black/10">
        @foreach($cart as $id => $item)
            @php $quantity = (int) ($item['quantity'] ?? 1); @endphp
            <li class="flex gap-4 py-4" data-cart-item="{{ $id }}">
                <img src="{{ $item['image'] ?? 'https://placehold.co/100x130' }}" alt="{{ $item['name'] }}" loading="lazy" class="w-20 h-28 object-cover rounded-lg bg-blush shrink-0">
                <div class="flex-1 min-w-0 flex flex-col">
                    <div class="flex justify-between gap-3">
                        <p class="text-sm font-medium leading-snug">{{ $item['name'] }}</p>
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <button aria-label="Remove {{ $item['name'] }}" class="text-black/40 hover:text-rose transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
                            </button>
                        </form>
                    </div>
                    <p class="text-sm text-muted mt-0.5">Rs. {{ number_format($item['price']) }}</p>
                    <div class="mt-auto flex items-center justify-between">
                        <form action="{{ route('cart.update') }}" method="POST" class="inline-flex items-center border border-black/15 rounded-full" aria-label="Update {{ $item['name'] }} quantity">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <button type="button" data-quantity-step="-1" @disabled($quantity <= 1) aria-label="Decrease quantity" class="w-8 h-8 flex items-center justify-center disabled:opacity-30">−</button>
                            <input type="number" name="quantity" value="{{ $quantity }}" min="1" max="99" required inputmode="numeric"
                                   data-cart-quantity aria-label="Quantity for {{ $item['name'] }}"
                                   class="w-10 text-center text-sm border-0 bg-transparent focus:outline-none focus:ring-1 focus:ring-plum rounded">
                            <button type="button" data-quantity-step="1" @disabled($quantity >= 99) aria-label="Increase quantity" class="w-8 h-8 flex items-center justify-center">+</button>
                        </form>
                        <p class="text-sm font-semibold">Rs. {{ number_format($item['price'] * $quantity) }}</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    <div class="border-t border-black/10 bg-white px-5 pt-4 pb-5" style="padding-bottom: max(1.25rem, env(safe-area-inset-bottom))">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-muted">Total</span>
            <span class="font-serif text-2xl">Rs. {{ number_format($summary['total']) }}</span>
        </div>
        <a href="{{ route('checkout.index') }}" data-checkout-link class="block text-center bg-plum text-cream py-3.5 rounded-full font-medium hover:bg-ink transition">Checkout</a>
        <a href="{{ route('cart.index') }}" class="block text-center text-sm mt-3 text-muted hover:text-rose transition">View full cart</a>
    </div>
@endif
