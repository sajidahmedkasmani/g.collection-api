<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FAF7F2">
    <meta name="google-site-verification" content="34nT_JEFzVeXLPCWFWLIy66AtSTk3QgX34tg0nbr8uw" />
    <!-- <title>G.Collection | Women's Clothing & Bags Online in Pakistan</title> -->
    <meta name="title" content="G.Collection | Women's Clothing & Bags Online in Pakistan">
    <meta name="description" content="Shop stylish women's clothing, handbags and fashion accessories at G.Collection. Discover the latest fashion trends with delivery across Pakistan.">
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
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TK2W4MDWHH"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-TK2W4MDWHH');
    </script>
</head>

<body class="font-sans antialiased"
      x-data="{ menu:false, cart:false, acc:false }"
      x-init="if (sessionStorage.getItem('gc_cart')) { sessionStorage.removeItem('gc_cart'); cart = true }"
      @keydown.escape.window="menu=false; cart=false; acc=false"
      :class="(menu || cart) ? 'overflow-hidden' : ''">

    @php
        $gcCart  = session('cart', []);
        $gcSummary = \App\Support\CartSummary::for($gcCart);
        $gcCount = $gcSummary['item_count'];
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
        @if(setting('announcement_enabled', '1') == '1' && setting('announcement_text'))
            <div class="bg-plum text-cream text-[11px] tracking-[0.2em] uppercase text-center py-2 px-4">
                {{ setting('announcement_text', 'Free shipping on orders above Rs. 10,000') }}
            </div>
        @endif
    <!-- ANNOUNCEMENT BAR -->

    <!-- HEADER -->
    <header class="bg-cream/95 backdrop-blur border-b border-black/10 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-serif text-2xl tracking-tight">{{ setting('site_name', 'G. Collection') }}</a>

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
                        <span data-cart-badge class="absolute -top-2 -right-2 bg-rose text-white text-[10px] font-medium rounded-full min-w-4 h-4 px-1 flex items-center justify-center">{{ $gcCount }}</span>
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

            <div id="gc-cart-drawer-content" class="flex-1 min-h-0 flex flex-col">
                @include('frontend.partials.cart-drawer-content', ['cart' => $gcCart, 'summary' => $gcSummary])
            </div>
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

    <div id="gc-checkout-modal" class="hidden fixed inset-0 z-[90] bg-ink/50 backdrop-blur-sm p-3 sm:p-6" role="dialog" aria-modal="true" aria-label="Checkout">
        <div class="relative bg-cream rounded-2xl max-w-5xl max-h-full overflow-y-auto mx-auto">
            <button type="button" data-close-checkout class="sticky top-3 float-right z-10 mr-3 mt-3 bg-white rounded-full w-10 h-10 shadow" aria-label="Close checkout">×</button>
            <div id="gc-checkout-content"></div>
        </div>
    </div>

    <div id="gc-async-feedback" class="fixed bottom-5 left-1/2 -translate-x-1/2 z-[100] hidden rounded-xl px-5 py-3 text-sm shadow-lg" role="status" aria-live="polite"></div>

    <!-- FOOTER -->
    <footer class="bg-ink text-cream {{ $gcSkipDrawer ? '' : 'pb-[calc(4rem+env(safe-area-inset-bottom))] md:pb-0' }}">
        <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-2 md:grid-cols-5 gap-10">
            <div class="col-span-2 md:col-span-1">
                <p class="font-serif text-3xl mb-3">{{ setting('site_name', 'G. Collection') }}</p>
                <p class="text-sm text-cream/60 leading-relaxed max-w-xs">{{ setting('footer_about', "A full collection of stitched and unstitched ladies' clothing from Karachi, Pakistan.") }}</p>
                @if(setting('social_instagram') || setting('social_facebook') || setting('social_tiktok'))
                    <div class="flex items-center gap-3 mt-4 text-cream/70">
                        @if(setting('social_instagram'))
                            <a href="{{ setting('social_instagram') }}" target="_blank" rel="noopener" class="hover:text-rose transition" aria-label="Instagram">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        @endif
                        @if(setting('social_facebook'))
                            <a href="{{ setting('social_facebook') }}" target="_blank" rel="noopener" class="hover:text-rose transition" aria-label="Facebook">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                        @if(setting('social_tiktok'))
                            <a href="{{ setting('social_tiktok') }}" target="_blank" rel="noopener" class="hover:text-rose transition" aria-label="TikTok">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
            <!-- Shop -->
                <div>
                    <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Shop</p>
                    <ul class="space-y-2 text-sm text-cream/80">
                        <li><a href="{{ route('shop') }}" class="hover:text-rose">All products</a></li>
                        @foreach($gcCats as $slug => $catLabel)
                            <li><a href="{{ route('shop') }}?category={{ $slug }}" class="hover:text-rose">{{ $catLabel }}</a></li>
                        @endforeach
                    </ul>
                </div>
            <!-- Shop -->

            <!-- Customer Support -->
                <div>
                    <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Customer support</p>
                    <ul class="space-y-2 text-sm text-cream/80">
                        <li><a href="{{ route('contact') }}" class="hover:text-rose">Contact us</a></li>
                        <li><a href="{{ route('faqs') }}" class="hover:text-rose">FAQs</a></li>
                        <li><a href="{{ route('shipping') }}" class="hover:text-rose">Shipping information</a></li>
                        <li><a href="{{ route('returns') }}" class="hover:text-rose">Returns &amp; exchanges</a></li>
                    </ul>
                </div>
            <!-- Customer Support -->

            <!-- Policies -->
                <div>
                    <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Policies</p>
                    <ul class="space-y-2 text-sm text-cream/80">
                        <li><a href="{{ route('shipping') }}" class="hover:text-rose">Shipping Policy</a></li>
                        <li><a href="{{ route('returns') }}" class="hover:text-rose">Returns & Exchanges</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-rose">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-rose">Terms & Conditions</a></li>
                    </ul>
                </div>
            <!-- Policies -->

            <!-- Contact -->
                <div>
                    <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Contact</p>
                    <ul class="space-y-2 text-sm text-cream/80">
                        <li>{{ setting('contact_address', 'Karachi, Pakistan') }}</li>
                        <li>{{ setting('contact_hours', 'Mon–Sat, 11:00 AM – 9:00 PM') }}</li>
                        <li><a href="{{ whatsapp_link() }}" target="_blank" rel="noopener" class="hover:text-rose">WhatsApp {{ setting('contact_phone', '+92 334 7779231') }}</a></li>
                        <li><a href="mailto:{{ setting('contact_email', 'support@gdotcollection.com') }}" class="hover:text-rose">{{ setting('contact_email', 'support@gdotcollection.com') }}</a></li>
                    </ul>
                </div>
            <!-- Contact -->
        </div>
        <div class="border-t border-cream/10 max-w-7xl mx-auto px-6 py-6 flex flex-col md:flex-row justify-between gap-3 text-xs text-cream/50">
            <span>© {{ date('Y') }} {{ setting('copyright_text', 'G. Collection. All rights reserved.') }}</span>
            <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;" class="hover:text-rose">Back to top ↑</a>
        </div>
    </footer>

    <script>
        (() => {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const feedback = document.getElementById('gc-async-feedback');
            const checkoutModal = document.getElementById('gc-checkout-modal');
            const checkoutContent = document.getElementById('gc-checkout-content');
            const checkoutPath = @json(route('checkout.index', [], false));
            let feedbackTimer;

            const showMessage = (message, isError = false) => {
                if (!feedback) return;
                feedback.textContent = message;
                feedback.className = `fixed bottom-5 left-1/2 -translate-x-1/2 z-[100] rounded-xl px-5 py-3 text-sm shadow-lg ${isError ? 'bg-red-700 text-white' : 'bg-ink text-white'}`;
                clearTimeout(feedbackTimer);
                feedbackTimer = setTimeout(() => feedback.classList.add('hidden'), 3500);
            };

            const closeCart = () => {
                if (window.Alpine) window.Alpine.$data(document.body).cart = false;
            };

            const updateCart = (data) => {
                document.querySelectorAll('[aria-label^="Cart,"]').forEach((link) => {
                    link.setAttribute('aria-label', `Cart, ${data.summary.item_count} items`);
                    let badge = link.querySelector('[data-cart-badge]');
                    if (data.summary.item_count) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.dataset.cartBadge = '';
                            badge.className = 'absolute -top-2 -right-2 bg-rose text-white text-[10px] font-medium rounded-full min-w-4 h-4 px-1 flex items-center justify-center';
                            link.appendChild(badge);
                        }
                        badge.textContent = data.summary.item_count;
                    } else if (badge) {
                        badge.remove();
                    }
                });

                const drawer = document.getElementById('gc-cart-drawer-content');
                if (drawer) drawer.innerHTML = data.drawer_html;
                const page = document.getElementById('gc-cart-page-content');
                if (page) page.outerHTML = data.page_html;
                window.dispatchEvent(new CustomEvent('gc:cart-updated', {detail: data}));
            };

            document.addEventListener('submit', async (event) => {
                const form = event.target;
                const action = new URL(form.action, window.location.href);

                if (['/cart/add', '/cart/update', '/cart/remove'].includes(action.pathname)) {
                    event.preventDefault();
                    const button = form.querySelector('button[type="submit"], button:not([type])');
                    if (button) button.disabled = true;
                    try {
                        const formData = new FormData(form);
                        if (event.submitter?.name) formData.set(event.submitter.name, event.submitter.value);
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                            body: formData,
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Could not update your cart.');
                        updateCart(data);
                        if (action.pathname === '/cart/add' && window.Alpine) window.Alpine.$data(document.body).cart = true;
                        showMessage(data.message);
                    } catch (error) {
                        showMessage(error.message, true);
                    } finally {
                        if (button) button.disabled = false;
                    }
                    return;
                }

                if (action.pathname === checkoutPath) {
                    event.preventDefault();
                    const submit = form.querySelector('[type="submit"]');
                    if (submit) submit.disabled = true;
                    form.querySelectorAll('[data-checkout-error]').forEach((node) => node.textContent = '');
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                            body: new FormData(form),
                        });
                        const data = await response.json();
                        if (response.status === 422 && data.errors) {
                            Object.entries(data.errors).forEach(([field, messages]) => {
                                const target = form.querySelector(`[data-checkout-error="${field}"]`);
                                if (target) target.textContent = messages[0];
                            });
                            if (data.message) showMessage(data.message, true);
                            return;
                        }
                        if (!response.ok) throw new Error(data.message || 'Could not place your order.');
                        window.location.assign(data.redirect);
                    } catch (error) {
                        showMessage(error.message, true);
                    } finally {
                        if (submit) submit.disabled = false;
                    }
                    return;
                }

                if (form.matches('[data-newsletter-form]')) {
                    event.preventDefault();
                    const submit = form.querySelector('[type="submit"]');
                    if (submit) submit.disabled = true;
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                            body: new FormData(form),
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Could not subscribe.');
                        showMessage(data.message);
                        form.reset();
                    } catch (error) {
                        showMessage(error.message, true);
                    } finally {
                        if (submit) submit.disabled = false;
                    }
                }
            });

            document.addEventListener('change', (event) => {
                if (event.target.matches('[data-cart-quantity]')) {
                    event.target.form.requestSubmit();
                }
            });

            document.addEventListener('click', async (event) => {
                const quantityStep = event.target.closest('[data-quantity-step]');
                if (quantityStep) {
                    const form = quantityStep.form;
                    const quantity = form.querySelector('[data-cart-quantity]');
                    quantity.value = Math.max(
                        Number(quantity.min),
                        Math.min(Number(quantity.max), Number(quantity.value) + Number(quantityStep.dataset.quantityStep))
                    );
                    form.requestSubmit();
                    return;
                }

                const closeButton = event.target.closest('[data-close-cart]');
                if (closeButton) {
                    closeCart();
                    return;
                }

                const closeCheckout = event.target.closest('[data-close-checkout]');
                if (closeCheckout || event.target === checkoutModal) {
                    checkoutModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    return;
                }

                const checkoutLink = event.target.closest('[data-checkout-link]');
                if (checkoutLink && !event.metaKey && !event.ctrlKey && !event.shiftKey && event.button === 0) {
                    event.preventDefault();
                    try {
                        const url = new URL(checkoutLink.href);
                        url.searchParams.set('partial', '1');
                        const response = await fetch(url, {headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}});
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Could not open checkout.');
                        checkoutContent.innerHTML = data.html;
                        checkoutModal.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden');
                        closeCart();
                    } catch (error) {
                        showMessage(error.message, true);
                    }
                }
            });

        })();
    </script>
    @stack('scripts')
</body>
</html>