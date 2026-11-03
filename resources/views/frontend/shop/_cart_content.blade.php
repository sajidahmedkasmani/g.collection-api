<div id="gc-cart-page-content">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-8 md:pt-14">
        @if(empty($cart))
            <div class="text-center py-16 md:py-24">
                <div class="mx-auto w-36 h-48 arch bg-blush flex items-end justify-center pb-10">
                    <svg class="w-10 h-10 text-plum" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                </div>
                <h1 class="font-serif text-4xl mt-8">Your cart is empty</h1>
                <p class="text-muted mt-2">Pieces you add will wait for you here.</p>
                <a href="{{ route('shop') }}" class="inline-block mt-7 bg-plum text-cream px-8 py-3.5 rounded-full text-sm font-medium hover:bg-ink transition">Start shopping</a>
            </div>
        @else
            <div class="flex items-end justify-between mb-8">
                <h1 class="font-serif text-4xl md:text-5xl">Your cart</h1>
                <p class="text-sm text-muted pb-1"><span data-cart-count>{{ $summary['item_count'] }}</span> {{ $summary['item_count'] == 1 ? 'item' : 'items' }}</p>
            </div>

            <div class="grid lg:grid-cols-[1fr_380px] gap-10 lg:gap-14 items-start">
                <section aria-label="Cart items">
                    <div class="divide-y divide-black/10 border-y border-black/10">
                        @foreach($cart as $id => $item)
                            @php $quantity = (int) ($item['quantity'] ?? 1); @endphp
                            <article class="flex gap-4 sm:gap-6 py-5" data-cart-item="{{ $id }}">
                                <img src="{{ $item['image'] ?? 'https://placehold.co/200x260' }}" alt="{{ $item['name'] }}" loading="lazy"
                                     class="w-24 h-32 sm:w-28 sm:h-36 object-cover rounded-xl bg-blush shrink-0">
                                <div class="flex-1 min-w-0 flex flex-col">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <h2 class="font-medium leading-snug">{{ $item['name'] }}</h2>
                                            <p class="text-sm text-muted mt-1">Rs. {{ number_format($item['price']) }}</p>
                                        </div>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $id }}">
                                            <button class="text-xs text-muted hover:text-rose underline underline-offset-4 transition" aria-label="Remove {{ $item['name'] }}">Remove</button>
                                        </form>
                                    </div>
                                    <div class="mt-auto pt-4 flex items-center justify-between">
                                        <form action="{{ route('cart.update') }}" method="POST" class="inline-flex items-center border border-black/15 rounded-full bg-white">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $id }}">
                                            <button name="quantity" value="{{ max(1, $quantity - 1) }}" @disabled($quantity <= 1) aria-label="Decrease quantity"
                                                    class="w-10 h-10 flex items-center justify-center text-lg hover:text-rose disabled:opacity-30 disabled:hover:text-ink transition">−</button>
                                            <span class="w-8 text-center text-sm font-medium" aria-live="polite">{{ $quantity }}</span>
                                            <button name="quantity" value="{{ min(99, $quantity + 1) }}" @disabled($quantity >= 99) aria-label="Increase quantity"
                                                    class="w-10 h-10 flex items-center justify-center text-lg hover:text-rose transition">+</button>
                                        </form>
                                        <p class="font-semibold">Rs. {{ number_format($item['price'] * $quantity) }}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 mt-6 text-sm text-muted hover:text-rose transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M15 6l-6 6 6 6"/></svg>
                        Continue shopping
                    </a>
                </section>

                <aside class="lg:sticky lg:top-24 bg-white rounded-2xl border border-black/5 shadow-[0_10px_40px_-20px_rgba(91,26,74,.25)] p-6">
                    <h2 class="font-serif text-2xl mb-5">Order summary</h2>
                    <div class="mb-6">
                        <p class="text-sm">
                            @if($summary['amount_until_free_shipping'] > 0)
                                Add <strong>Rs. {{ number_format($summary['amount_until_free_shipping']) }}</strong> more for free shipping
                            @else
                                You've unlocked free shipping
                            @endif
                        </p>
                        <div class="h-1.5 rounded-full bg-blush mt-2 overflow-hidden">
                            <div class="h-full bg-rose rounded-full transition-all duration-300" style="width: {{ $summary['shipping_progress'] }}%"></div>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm border-t border-black/10 pt-4">
                        <div class="flex justify-between"><span class="text-muted">Subtotal</span><span>Rs. {{ number_format($summary['subtotal']) }}</span></div>
                        <div class="flex justify-between"><span class="text-muted">Shipping</span><span>{{ $summary['shipping'] > 0 ? 'Rs. '.number_format($summary['shipping']) : 'Free' }}</span></div>
                    </div>
                    <div class="flex items-center justify-between py-4 border-t border-black/10 mt-4">
                        <span class="text-muted">Total</span>
                        <span class="font-serif text-3xl">Rs. {{ number_format($summary['total']) }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" data-checkout-link class="block text-center bg-plum text-cream py-4 rounded-full font-medium hover:bg-ink transition">Checkout</a>
                    <p class="text-xs text-muted text-center mt-3">Cash on delivery available</p>
                </aside>
            </div>
        @endif
    </div>

    @if(!empty($cart))
        <div class="lg:hidden sticky bottom-0 z-30 mt-10 bg-cream/95 backdrop-blur border-t border-black/10 px-4 py-3 flex items-center justify-between gap-4"
             style="padding-bottom: max(.75rem, env(safe-area-inset-bottom))">
            <div>
                <p class="text-xs text-muted">Total</p>
                <p class="font-serif text-2xl leading-none">Rs. {{ number_format($summary['total']) }}</p>
            </div>
            <a href="{{ route('checkout.index') }}" data-checkout-link class="bg-plum text-cream px-8 py-3 rounded-full text-sm font-medium hover:bg-ink transition">Checkout</a>
        </div>
    @endif
    <div class="h-10 lg:h-16"></div>
</div>
