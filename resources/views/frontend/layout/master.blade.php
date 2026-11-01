<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FAF7F2">
    <meta name="google-site-verification" content="34nT_JEFzVeXLPCWFWLIy66AtSTk3QgX34tg0nbr8uw" />
    <title>@yield('title', 'G. Collection — Wear the Story, Every Stitch')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">


    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/site.webmanifest">


    

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.14.8/dist/cdn.min.js" defer></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // cream: '#FAF7F2',
                        cream: 'rgb(250 243 234)',
                        ink: '#1F1215',
                        plum: '#5B1A4A',
                        // rose is a full scale now, so both `text-rose` and `bg-rose-600` work
                        rose: {
                            DEFAULT: '#D9558A',
                            50: '#FDF2F7', 100: '#F8DCE8', 200: '#F2BAD1', 300: '#EA93B6', 400: '#E174A0',
                            500: '#D9558A', 600: '#C23E75', 700: '#A32F5F', 800: '#82244C', 900: '#5B1A4A',
                        },
                        blush: '#F8DCE8',
                        royal: '#2D2E8E',
                        accent: '#D9558A',
                        muted: '#6B6B6B',
                    },
                    fontFamily: {
                        serif: ['"DM Serif Display"', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        [x-cloak]{display:none!important}
        /* Anything wider than the screen (sliders, marquees) must never widen the page:
           otherwise Chrome zooms out and fixed bars/menus stretch past the content. */
        html{overflow-x:hidden;max-width:100%}
        body{background:#FAF7F2;color:#1F1215;overflow-x:clip;max-width:100%;position:relative}
        .marquee{display:flex;width:max-content;animation:marquee 40s linear infinite}
        .marquee-slow{animation-duration:60s}
        @keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
        summary::-webkit-details-marker{display:none}
        .no-scrollbar::-webkit-scrollbar{display:none}
        .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
        .arch{border-radius:9999px 9999px 0 0}
        .nav-link{position:relative}
        .nav-link::after{content:'';position:absolute;left:0;right:0;bottom:-6px;height:1.5px;background:#D9558A;transform:scaleX(0);transform-origin:left;transition:transform .3s ease}
        .nav-link:hover::after,.nav-link.active::after{transform:scaleX(1)}
        @media (prefers-reduced-motion:reduce){.marquee{animation:none}*{transition-duration:.01ms!important}}
    </style>
    @stack('head')
</head>

<body class="font-sans antialiased"
      x-data="{ menu:false, cart:false, acc:false }"
      x-init="if (sessionStorage.getItem('gc_cart')) { sessionStorage.removeItem('gc_cart'); cart = true }"
      @keydown.escape.window="menu=false; cart=false; acc=false"
      :class="(menu || cart) ? 'overflow-hidden' : ''">

@php
    $gcCart  = session('cart', []);
    $gcCount = collect($gcCart)->sum(fn ($i) => $i['quantity'] ?? 1);
    $gcTotal = collect($gcCart)->sum(fn ($i) => ($i['price'] ?? 0) * ($i['quantity'] ?? 1));
    $gcFree  = 10000;
    $gcLeft  = max(0, $gcFree - $gcTotal);
    $gcPct   = min(100, (int) round($gcTotal / $gcFree * 100));
    $customer = auth('customer')->user();
    $gcNav = [
        ['Home', 'home'],
        ['About Us', 'aboutus'],
        ['Shop', 'shop'],
        ['Blogs', 'blogs'],
        ['Contact', 'contact'],
    ];
    $gcCats = ['stitched' => 'Stitched', 'unstitched' => 'Unstitched', 'accessories' => 'Accessories'];
    $gcSkipDrawer = request()->routeIs('cart.*', 'checkout.*');
    $userSvg = '<svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
@endphp

    <!-- ANNOUNCEMENT BAR -->
    <div class="bg-plum text-cream text-[11px] tracking-[0.2em] uppercase text-center py-2 px-4">
        Free shipping on orders above Rs. 10,000
    </div>

    <!-- HEADER -->
    <header class="bg-cream/95 backdrop-blur border-b border-black/10 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-serif text-2xl tracking-tight">G. Collection</a>

            <nav class="hidden md:flex items-center gap-9 text-sm" aria-label="Main">
                @foreach($gcNav as [$label, $name])
                    <a href="{{ route($name) }}" class="nav-link hover:text-rose transition {{ request()->routeIs($name) ? 'active text-rose' : '' }}">{{ $label }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-4 sm:gap-5">
                <a href="{{ route('shop') }}" aria-label="Search" class="hover:text-rose transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                </a>
                <a href="#" aria-label="Wishlist" class="hidden md:block hover:text-rose transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 21s-7-4.6-9.3-9A5.3 5.3 0 0 1 12 6a5.3 5.3 0 0 1 9.3 6C19 16.4 12 21 12 21Z"/></svg>
                </a>

                {{-- Cart (on mobile it lives in the bottom bar, except on cart/checkout pages where the bar is hidden) --}}
                <a href="{{ route('cart.index') }}" aria-label="Cart, {{ $gcCount }} items"
                   class="relative hover:text-rose transition {{ $gcSkipDrawer ? '' : 'hidden md:block' }}"
                   @unless($gcSkipDrawer) @click.prevent="cart = true; menu = false" @endunless>
                    <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                    @if($gcCount > 0)
                        <span class="absolute -top-2 -right-2 bg-rose text-white text-[10px] font-medium rounded-full min-w-4 h-4 px-1 flex items-center justify-center">{{ $gcCount }}</span>
                    @endif
                </a>

                {{-- Account --}}
                @if($customer)
                    <a href="{{ route('customer.dashboard') }}" aria-label="My account" class="{{ $gcSkipDrawer ? 'md:hidden' : 'hidden' }} hover:text-rose transition">{!! $userSvg !!}</a>

                    {{-- Desktop: hover card --}}
                    <div class="relative hidden md:block"
                         @mouseenter="acc = true" @mouseleave="acc = false"
                         @focusin="acc = true" @focusout="if (!$el.contains($event.relatedTarget)) acc = false">
                        <a href="{{ route('customer.dashboard') }}" aria-label="My account" aria-haspopup="true" :aria-expanded="acc"
                           class="flex items-center gap-2 hover:text-rose transition" :class="acc ? 'text-rose' : ''">
                            {!! $userSvg !!}
                        </a>

                        <div x-cloak x-show="acc"
                             x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                             class="absolute right-0 top-full pt-4 w-72 z-50">
                            <div class="bg-white rounded-2xl border border-black/10 shadow-[0_20px_50px_-15px_rgba(91,26,74,.35)] overflow-hidden">
                                <div class="p-5 flex items-center gap-4 bg-blush/50">
                                    <div class="w-12 h-14 arch bg-plum text-cream font-serif text-xl flex items-end justify-center pb-1.5 shrink-0">
                                        {{ mb_strtoupper(mb_substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-serif text-lg leading-tight truncate">{{ $customer->name }}</p>
                                        <p class="text-xs text-muted truncate">{{ $customer->email }}</p>
                                        @if(!empty($customer->phone) || !empty($customer->city))
                                            <p class="text-xs text-muted truncate mt-0.5">{{ collect([$customer->phone ?? null, $customer->city ?? null])->filter()->implode(' · ') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-2 text-sm">
                                    <a href="{{ route('customer.dashboard') }}" class="block px-3 py-2.5 rounded-lg hover:bg-cream hover:text-rose transition">My account</a>
                                    <a href="{{ route('customer.dashboard') }}#orders" class="block px-3 py-2.5 rounded-lg hover:bg-cream hover:text-rose transition">My orders</a>
                                    <a href="{{ route('customer.profile.edit') }}" class="block px-3 py-2.5 rounded-lg hover:bg-cream hover:text-rose transition">Edit profile</a>
                                    @auth('web')
                                        @if(auth('web')->user()->is_admin)
                                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 rounded-lg text-rose hover:bg-cream transition">Admin panel</a>
                                        @endif
                                    @endauth
                                </div>
                                <form method="POST" action="{{ route('customer.logout') }}" class="border-t border-black/10 p-2">
                                    @csrf
                                    <button class="w-full text-left px-3 py-2.5 rounded-lg text-sm text-muted hover:bg-cream hover:text-ink transition">Log out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('customer.login') }}" aria-label="Log in" title="Log in"
                       class="hover:text-rose transition {{ $gcSkipDrawer ? '' : 'hidden md:block' }}">{!! $userSvg !!}</a>
                @endif
            </div>
        </div>
    </header>

    @unless($gcSkipDrawer)
    <!-- MOBILE BOTTOM BAR -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-[55] bg-cream/95 backdrop-blur border-t border-black/10"
         style="padding-bottom: env(safe-area-inset-bottom)" aria-label="Quick navigation">
        <ul class="grid grid-cols-5 text-[11px]">
            <li>
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 py-2.5 {{ request()->routeIs('home') ? 'text-plum font-medium' : 'text-ink/60' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 11 12 4l9 7M5 10v10h14V10"/></svg>
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('shop') }}" class="flex flex-col items-center gap-1 py-2.5 {{ request()->routeIs('shop') ? 'text-plum font-medium' : 'text-ink/60' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="4" y="4" width="6.5" height="6.5" rx="1"/><rect x="13.5" y="4" width="6.5" height="6.5" rx="1"/><rect x="4" y="13.5" width="6.5" height="6.5" rx="1"/><rect x="13.5" y="13.5" width="6.5" height="6.5" rx="1"/></svg>
                    Shop
                </a>
            </li>
            <li>
                <button type="button" @click="cart = true; menu = false" class="w-full flex flex-col items-center gap-1 py-2.5 text-ink/60" aria-label="Open cart, {{ $gcCount }} items">
                    <span class="relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                        @if($gcCount > 0)
                            <span class="absolute -top-1.5 -right-2.5 bg-rose text-white text-[10px] font-medium rounded-full min-w-4 h-4 px-1 flex items-center justify-center">{{ $gcCount }}</span>
                        @endif
                    </span>
                    Cart
                </button>
            </li>
            <li>
                <a href="{{ $customer ? route('customer.dashboard') : route('customer.login') }}"
                   class="flex flex-col items-center gap-1 py-2.5 {{ request()->routeIs('customer.*') ? 'text-plum font-medium' : 'text-ink/60' }}">
                    {!! str_replace('w-[22px] h-[22px]', 'w-6 h-6', $userSvg) !!}
                    {{ $customer ? 'Account' : 'Log in' }}
                </a>
            </li>
            <li>
                <button type="button" @click="menu = true; cart = false" class="w-full flex flex-col items-center gap-1 py-2.5 text-ink/60" aria-haspopup="dialog" :aria-expanded="menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
                    Menu
                </button>
            </li>
        </ul>
    </nav>

    <!-- MOBILE MENU SHEET (slides up from the bottom, same links as desktop) -->
    <div class="md:hidden fixed inset-0 z-[60]" :class="menu ? '' : 'pointer-events-none'">
        <div x-cloak x-show="menu" x-transition.opacity.duration.250ms class="absolute inset-0 bg-ink/40 backdrop-blur-sm" @click="menu = false"></div>

        <div x-cloak x-show="menu"
             x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
             x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
             class="absolute inset-x-0 bottom-0 max-h-[88%] overflow-y-auto bg-cream rounded-t-3xl shadow-[0_-20px_50px_-20px_rgba(31,18,21,.35)] px-5 pt-3"
             style="padding-bottom: max(1.5rem, env(safe-area-inset-bottom))"
             role="dialog" aria-modal="true" aria-label="Menu">

            <div class="mx-auto w-10 h-1 rounded-full bg-black/15 mb-3"></div>
            <div class="flex items-center justify-between mb-1">
                <p class="font-serif text-2xl">Menu</p>
                <button type="button" @click="menu = false" aria-label="Close menu" class="p-1 -mr-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>

            <nav aria-label="Mobile">
                @foreach($gcNav as [$label, $name])
                    <a href="{{ route($name) }}" class="flex items-center justify-between py-3.5 border-b border-black/10 text-lg {{ request()->routeIs($name) ? 'text-rose font-medium' : '' }}">
                        {{ $label }}
                        <svg class="w-5 h-5 text-black/30" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>
                    </a>
                @endforeach
            </nav>

            <div class="flex flex-wrap gap-2 py-4">
                @foreach($gcCats as $slug => $catLabel)
                    <a href="{{ route('shop') }}?category={{ $slug }}" class="px-4 py-2 rounded-full border border-black/15 text-sm hover:border-plum hover:text-plum transition">{{ $catLabel }}</a>
                @endforeach
            </div>

            @if($customer)
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-blush/50">
                    <div class="w-11 h-14 arch bg-plum text-cream font-serif text-lg flex items-end justify-center pb-1 shrink-0">{{ mb_strtoupper(mb_substr($customer->name, 0, 1)) }}</div>
                    <div class="min-w-0">
                        <p class="font-serif text-lg leading-tight truncate">{{ $customer->name }}</p>
                        <p class="text-xs text-muted truncate">{{ $customer->email }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-3 text-sm">
                    <a href="{{ route('customer.dashboard') }}" class="text-center py-3 rounded-xl border border-black/15">My account</a>
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button class="w-full py-3 rounded-xl border border-black/15 text-muted">Log out</button>
                    </form>
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <a href="{{ route('customer.login') }}" class="text-center py-3.5 rounded-xl bg-plum text-cream font-medium">Log in</a>
                    <a href="{{ route('customer.register') }}" class="text-center py-3.5 rounded-xl border border-black/15">Create account</a>
                </div>
            @endif

            <div class="pt-5 text-sm text-muted">
                <a href="https://wa.me/923347779231" class="text-ink font-medium">WhatsApp +92 334 7779231</a>
                <p>Mon–Sat, 11:00 AM – 9:00 PM</p>
            </div>
        </div>
    </div>
    @endunless

    <!-- CART DRAWER -->
    <div class="fixed inset-0 z-[70]" :class="cart ? '' : 'pointer-events-none'">
        <div x-cloak x-show="cart" x-transition.opacity.duration.300ms class="absolute inset-0 bg-ink/40 backdrop-blur-sm" @click="cart = false"></div>

        <aside x-cloak x-show="cart"
               x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
               class="absolute right-0 top-0 h-full w-[92%] max-w-md bg-cream flex flex-col shadow-2xl" role="dialog" aria-modal="true" aria-label="Cart">

            <div class="flex items-center justify-between px-5 py-4 border-b border-black/10">
                <h2 class="font-serif text-2xl">Your cart <span class="text-muted text-base font-sans">({{ $gcCount }})</span></h2>
                <button @click="cart = false" aria-label="Close cart" class="p-1 -mr-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>

            @if(empty($gcCart))
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
                        @if($gcLeft > 0) Add <strong>Rs. {{ number_format($gcLeft) }}</strong> more for free shipping
                        @else You've unlocked free shipping @endif
                    </p>
                    <div class="h-1.5 rounded-full bg-blush mt-2 overflow-hidden"><div class="h-full bg-rose rounded-full transition-all duration-700" style="width: {{ $gcPct }}%"></div></div>
                </div>

                <ul class="flex-1 overflow-y-auto px-5 divide-y divide-black/10">
                    @foreach($gcCart as $id => $item)
                        @php $q = $item['quantity'] ?? 1; @endphp
                        <li class="flex gap-4 py-4">
                            <img src="{{ $item['image'] ?? 'https://placehold.co/100x130' }}" alt="{{ $item['name'] }}" loading="lazy" class="w-20 h-28 object-cover rounded-lg bg-blush shrink-0">
                            <div class="flex-1 min-w-0 flex flex-col">
                                <div class="flex justify-between gap-3">
                                    <p class="text-sm font-medium leading-snug">{{ $item['name'] }}</p>
                                    <form action="{{ route('cart.remove') }}" method="POST" @submit="sessionStorage.setItem('gc_cart','1')">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button aria-label="Remove {{ $item['name'] }}" class="text-black/40 hover:text-rose transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <p class="text-sm text-muted mt-0.5">Rs. {{ number_format($item['price']) }}</p>
                                <div class="mt-auto flex items-center justify-between">
                                    <form action="{{ route('cart.update') }}" method="POST" class="inline-flex items-center border border-black/15 rounded-full" @submit="sessionStorage.setItem('gc_cart','1')">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button name="quantity" value="{{ max(1, $q - 1) }}" @disabled($q <= 1) aria-label="Decrease quantity" class="w-8 h-8 flex items-center justify-center disabled:opacity-30">−</button>
                                        <span class="w-6 text-center text-sm">{{ $q }}</span>
                                        <button name="quantity" value="{{ $q + 1 }}" aria-label="Increase quantity" class="w-8 h-8 flex items-center justify-center">+</button>
                                    </form>
                                    <p class="text-sm font-semibold">Rs. {{ number_format($item['price'] * $q) }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="border-t border-black/10 bg-white px-5 pt-4 pb-5" style="padding-bottom: max(1.25rem, env(safe-area-inset-bottom))">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-muted">Total</span>
                        <span class="font-serif text-2xl">Rs. {{ number_format($gcTotal) }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="block text-center bg-plum text-cream py-3.5 rounded-full font-medium hover:bg-ink transition">Checkout</a>
                    <a href="{{ route('cart.index') }}" class="block text-center text-sm mt-3 text-muted hover:text-rose transition">View full cart</a>
                </div>
            @endif
        </aside>
    </div>

    <!-- FLASH -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6"><div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6"><div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div></div>
    @endif

    <main>@yield('content')</main>

    <!-- FOOTER -->
    <footer class="bg-ink text-cream {{ $gcSkipDrawer ? '' : 'pb-[calc(4rem+env(safe-area-inset-bottom))] md:pb-0' }}">
        <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-2 md:grid-cols-4 gap-10">
            <div class="col-span-2 md:col-span-1">
                <p class="font-serif text-3xl mb-3">G. Collection</p>
                <p class="text-sm text-cream/60 leading-relaxed max-w-xs">A full collection of stitched and unstitched ladies' clothing from Karachi, Pakistan.</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Shop</p>
                <ul class="space-y-2 text-sm text-cream/80">
                    <li><a href="{{ route('shop') }}" class="hover:text-rose">All products</a></li>
                    @foreach($gcCats as $slug => $catLabel)
                        <li><a href="{{ route('shop') }}?category={{ $slug }}" class="hover:text-rose">{{ $catLabel }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Customer support</p>
                <ul class="space-y-2 text-sm text-cream/80">
                    <li><a href="https://wa.me/923347779231" class="hover:text-rose">Contact us</a></li>
                    <li><a href="{{ route('home') }}#faq" class="hover:text-rose">FAQ</a></li>
                    <li><a href="#" class="hover:text-rose">Shipping information</a></li>
                    <li><a href="#" class="hover:text-rose">Returns &amp; exchanges</a></li>
                </ul>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Contact</p>
                <ul class="space-y-2 text-sm text-cream/80">
                    <li>Karachi, Pakistan</li>
                    <li>Mon–Sat, 11:00 AM – 9:00 PM</li>
                    <li><a href="https://wa.me/923347779231" class="hover:text-rose">WhatsApp +92 334 7779231</a></li>
                    <li><a href="mailto:support@gdotcollection.com" class="hover:text-rose">support@gdotcollection.com</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-cream/10 max-w-7xl mx-auto px-6 py-6 flex flex-col md:flex-row justify-between gap-3 text-xs text-cream/50">
            <span>© {{ date('Y') }} G. Collection. All rights reserved.</span>
            <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;" class="hover:text-rose">Back to top ↑</a>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>