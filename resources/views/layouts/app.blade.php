<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'G. Collection — Wear the Story, Every Stitch')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#FAF7F2',
                        ink: '#1F1215',
                        plum: '#5B1A4A',
                        rose: '#D9558A',
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
        body{background:#FAF7F2;color:#1F1215}
        .marquee{display:flex;width:max-content;animation:marquee 40s linear infinite}
        .marquee-slow{animation-duration:60s}
        @keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
        summary::-webkit-details-marker{display:none}
        .no-scrollbar::-webkit-scrollbar{display:none}
        .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
        .arch{border-radius:9999px 9999px 0 0}
        @media (prefers-reduced-motion:reduce){.marquee{animation:none}}
    </style>
</head>
<body class="font-sans antialiased">

{{-- ANNOUNCEMENT BAR --}}
<div class="bg-plum text-cream text-[11px] tracking-[0.2em] uppercase text-center py-2">
    Free shipping on orders above Rs. 10,000
</div>

{{-- HEADER --}}
<header class="bg-cream/95 backdrop-blur border-b border-black/10 sticky top-0 z-50" x-data="{ open:false }">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="font-serif text-2xl tracking-tight">G. Collection</a>

        <nav class="hidden md:flex items-center gap-8 text-sm">
            <a href="{{ route('shop') }}" class="hover:text-rose transition">Shop</a>
            <a href="{{ route('shop') }}?category=stitched" class="hover:text-rose transition">Stitched</a>
            <a href="{{ route('shop') }}?category=unstitched" class="hover:text-rose transition">Unstitched</a>
            <a href="{{ route('shop') }}?category=accessories" class="hover:text-rose transition">Accessories</a>
            <a href="https://wa.me/923347779231" class="hover:text-rose transition">Contact</a>
        </nav>

        <div class="flex items-center gap-5">
            <a href="{{ route('shop') }}" aria-label="Search" class="hidden md:block hover:text-rose">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            </a>
            <a href="#" aria-label="Wishlist" class="hidden md:block hover:text-rose">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 21s-7-4.6-9.3-9A5.3 5.3 0 0 1 12 6a5.3 5.3 0 0 1 9.3 6C19 16.4 12 21 12 21Z"/></svg>
            </a>
            <a href="{{ route('cart.index') }}" aria-label="Cart" class="relative hover:text-rose">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                @php $c = count(session('cart', [])); @endphp
                @if($c > 0)
                    <span class="absolute -top-2 -right-2 bg-rose text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">{{ $c }}</span>
                @endif
            </a>
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="hidden md:block text-xs text-rose font-medium">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="hidden md:inline">@csrf<button class="text-xs hover:text-rose">Logout</button></form>
            @else
                <a href="{{ route('login') }}" class="hidden md:block text-xs hover:text-rose">Login</a>
            @endauth
            <button class="md:hidden text-2xl" @click="open=!open" aria-label="Menu">☰</button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-cloak x-show="open" x-transition class="md:hidden border-t border-black/10 bg-cream px-6 py-4 flex flex-col gap-3 text-sm">
        <a href="{{ route('shop') }}">Shop</a>
        <a href="{{ route('shop') }}?category=stitched">Stitched</a>
        <a href="{{ route('shop') }}?category=unstitched">Unstitched</a>
        <a href="{{ route('shop') }}?category=accessories">Accessories</a>
        <a href="{{ route('cart.index') }}">Cart ({{ $c }})</a>
        @auth
            @if(auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}" class="text-rose">Admin</a>@endif
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-left">Logout</button></form>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </div>
</header>

{{-- FLASH --}}
@if(session('success'))
    <div class="max-w-7xl mx-auto mt-4 px-6"><div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div></div>
@endif
@if(session('error'))
    <div class="max-w-7xl mx-auto mt-4 px-6"><div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div></div>
@endif

<main>@yield('content')</main>

{{-- FOOTER --}}
<footer class="bg-ink text-cream">
    <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-2 md:grid-cols-4 gap-10">
        <div class="col-span-2 md:col-span-1">
            <p class="font-serif text-3xl mb-3">G. Collection</p>
            <p class="text-sm text-cream/60 leading-relaxed max-w-xs">A full collection of stitched and unstitched ladies' clothing from Karachi, Pakistan.</p>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Shop</p>
            <ul class="space-y-2 text-sm text-cream/80">
                <li><a href="{{ route('shop') }}" class="hover:text-rose">All products</a></li>
                <li><a href="{{ route('shop') }}?category=stitched" class="hover:text-rose">Stitched</a></li>
                <li><a href="{{ route('shop') }}?category=unstitched" class="hover:text-rose">Unstitched</a></li>
                <li><a href="{{ route('shop') }}?category=accessories" class="hover:text-rose">Accessories</a></li>
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
</body>
</html>