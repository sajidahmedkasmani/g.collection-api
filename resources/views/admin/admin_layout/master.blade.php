<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#7B47D8">
    <title>Admin — @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50:'#F4EFFD', 100:'#E9E0FB', 200:'#D3C2F7', 400:'#9B73E8', 500:'#8451DB', 600:'#7340C8', 700:'#5F32A8' },
                        bloom: { 400:'#FF7BA9', 500:'#F9528A' },
                        lav: '#EFEBF7',
                        ink: '#1F1B2E',
                    },
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                }
            }
        }
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('counter', (to) => ({
                v: 0,
                init() {
                    const d = 900, s = performance.now();
                    const step = (t) => {
                        const p = Math.min((t - s) / d, 1);
                        this.v = Math.round(to * (1 - Math.pow(1 - p, 3)));
                        if (p < 1) requestAnimationFrame(step);
                    };
                    requestAnimationFrame(step);
                }
            }));
        });
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        [x-cloak]{display:none!important}
        .rail{background:linear-gradient(180deg,#7B47D8 0%,#9A72E6 55%,#C9B2F5 100%)}
        .btn-bloom{background:linear-gradient(90deg,#FF7BA9,#F9528A);box-shadow:0 8px 18px -6px rgba(249,82,138,.55)}
        .soft{box-shadow:0 6px 24px -10px rgba(95,50,168,.18)}
        .lift{transition:transform .25s ease, box-shadow .25s ease}
        .lift:hover{transform:translateY(-3px);box-shadow:0 16px 30px -14px rgba(95,50,168,.35)}
        body{background:
            radial-gradient(900px 500px at 0% 0%, #F7F4FC 0%, transparent 60%),
            radial-gradient(800px 500px at 100% 100%, #E3D9F6 0%, transparent 60%), #EFEBF7;
            -webkit-tap-highlight-color:transparent}
        @keyframes rise{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
        .rise{opacity:0;animation:rise .55s cubic-bezier(.2,.7,.2,1) forwards;animation-delay:var(--d,0s)}
        @keyframes grow{from{transform:scaleY(0)}to{transform:scaleY(1)}}
        .grow{transform-origin:bottom;animation:grow .7s cubic-bezier(.2,.7,.2,1) both;animation-delay:var(--d,0s)}
        .scroll-thin::-webkit-scrollbar{width:6px;height:6px}
        .scroll-thin::-webkit-scrollbar-thumb{background:#D3C2F7;border-radius:9px}
        @media (prefers-reduced-motion:reduce){.rise,.grow{animation:none;opacity:1}}
    </style>
</head>
<body class="font-sans text-ink antialiased">
<!-- @php
    $nav = [
        ['Dashboard', 'admin.dashboard',      'admin.dashboard',   'M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10'],
        ['Products',  'admin.products.index', 'admin.products.*',  'M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 10L4 17V7m8 14V11'],
        ['Orders',    'admin.orders.index',   'admin.orders.*',    'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5h6M9 12h6M9 16h4'],
        ['Settings',  'admin.settings.edit',  'admin.settings.*',  'M12 15a3 3 0 100-6 3 3 0 000 6zM19.4 15a1.7 1.7 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.8-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.8 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1.1 1.7 1.7 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.8.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.8V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z'],
        ['Sliders',   'admin.sliders.index',  'admin.sliders.*',   'M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16l4-4 3 3 4-5 5 6M9 9h.01'],
        ['Users',     'admin.users.index',    'admin.users.*',     'M17 20v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2M10 10a4 4 0 100-8 4 4 0 000 8zM21 20v-2a4 4 0 00-3-3.870M16 2.130a4 4 0 010 7.750'],
    ];
    $navPermissions = [
        'admin.dashboard' => 'admin.dashboard',
        'admin.products.index' => 'products.view',
        'admin.orders.index' => 'orders.view',
        'admin.settings.edit' => 'settings.view',
        'admin.sliders.index' => 'sliders.view',
        'admin.users.index' => 'users.view',
    ];
    $user = auth()->user();
    $initial = strtoupper(substr($user->name ?? 'A', 0, 1));
@endphp -->

@php
    $nav = [
        ['Dashboard', 'admin.dashboard',      'admin.dashboard',   'M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10'],
        ['Products',  'admin.products.index', 'admin.products.*',  'M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 10L4 17V7m8 14V11'],
        ['Orders',    'admin.orders.index',   'admin.orders.*',    'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5h6M9 12h6M9 16h4'],
        ['Sliders',   'admin.sliders.index',  'admin.sliders.*',   'M4 5a1 1 0 011-1h14a1 1 0 011 1v10a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm4 14h8m-4-4v4'],
        ['CMS Content','admin.cms.index',     'admin.cms.*',       'M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm3 4h10M7 14h7'],
        ['FAQs',      'admin.faqs.index',     'admin.faqs.*',      'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['Settings',  'admin.settings.edit',  'admin.settings.*',  'M12 15a3 3 0 100-6 3 3 0 000 6zM19.4 15a1.7 1.7 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.8-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.8 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1.1 1.7 1.7 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.8.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.8V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z'],
        ['Users',     'admin.users.index',    'admin.users.*',     'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['Newsletters', 'admin.newsletters.index', 'admin.newsletters.*', 'M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z'],
    ];
    $navPermissions = [
        'admin.dashboard' => 'admin.dashboard',
        'admin.products.index' => 'products.view',
        'admin.orders.index' => 'orders.view',
        'admin.sliders.index' => 'sliders.view',
        'admin.cms.index' => 'cms.view',
        'admin.faqs.index' => 'faqs.view',
        'admin.settings.edit' => 'settings.view',
        'admin.users.index' => 'users.view',
        'admin.newsletters.index' => 'newsletter.manage',
    ];
    $user = auth()->user();
    $initial = strtoupper(substr($user->name ?? 'A', 0, 1));
@endphp

<div x-data="{ menu: false }"
     x-effect="document.body.classList.toggle('overflow-hidden', menu)"
     @keydown.escape.window="menu = false">

<div class="md:h-screen">
    <div class="md:overflow-hidden flex md:h-full min-h-screen">

        {{-- ICON RAIL (tablet + desktop) --}}
        <div class="rail hidden md:flex w-[72px] shrink-0 flex-col items-center py-5 gap-3 text-white/80">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 mb-4 rounded-full bg-white/25 flex items-center justify-center text-white font-semibold">G</a>
            @foreach($nav as [$label,$route,$pattern,$icon])
                @can($navPermissions[$route])
                <a href="{{ route($route) }}" title="{{ $label }}"
                   class="w-11 h-11 rounded-xl flex items-center justify-center transition {{ request()->routeIs($pattern) ? 'bg-white/25 text-white shadow-inner' : 'hover:bg-white/15' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                </a>
                @endcan
            @endforeach
            <a href="{{ route('home') }}" title="View site" class="mt-auto w-11 h-11 rounded-xl flex items-center justify-center hover:bg-white/15 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1h5"/></svg>
            </a>
        </div>

        {{-- SIDEBAR (large screens) --}}
        <aside class="hidden xl:flex w-[230px] shrink-0 bg-white flex-col px-5 py-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 mb-8 px-1">
                <span class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-bloom-400"></span>
                <span class="font-semibold tracking-wide">G. ADMIN</span>
            </a>

            @can('products.create')
            <a href="{{ route('admin.products.create') }}"
               class="btn-bloom text-white rounded-xl py-3 px-4 flex items-center justify-center gap-2 text-sm font-medium mb-7 hover:opacity-90 transition">
                <span class="text-lg leading-none">+</span> Add Product
            </a>
            @endcan

            <p class="text-[11px] text-gray-400 mb-3 px-1">Manage</p>
            <nav class="space-y-1 text-sm flex-1">
                @foreach($nav as [$label,$route,$pattern,$icon])
                    @can($navPermissions[$route])
                    <a href="{{ route($route) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs($pattern) ? 'bg-brand-50 text-brand-600 font-medium' : 'text-gray-500 hover:bg-gray-50 hover:text-ink' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                        {{ $label }}
                    </a>
                    @endcan
                @endforeach
            </nav>

            <div class="pt-5 border-t border-gray-100 text-sm space-y-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-ink transition">← Back to site</a>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-red-50 hover:text-red-600 transition">Logout</button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 min-w-0 flex flex-col bg-lav/70">
            <header class="sticky md:static top-0 z-30 h-16 md:h-[72px] shrink-0 px-4 md:px-8 flex items-center justify-between gap-3 bg-lav/90 backdrop-blur md:bg-transparent border-b border-white/70">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" @click="menu = true" aria-label="Open menu" aria-haspopup="dialog"
                            class="md:hidden w-10 h-10 rounded-xl bg-white soft flex flex-col items-center justify-center gap-[5px] shrink-0 active:scale-95 transition">
                        <span class="block w-[18px] h-[2px] rounded-full bg-ink"></span>
                        <span class="block w-[18px] h-[2px] rounded-full bg-ink"></span>
                        <span class="block w-[18px] h-[2px] rounded-full bg-ink"></span>
                    </button>
                    <h1 class="text-lg md:text-xl font-semibold truncate">@yield('title')</h1>
                </div>

                <div class="flex items-center gap-3">
                    @can('orders.view')
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="hidden lg:flex items-center gap-2 bg-white/80 rounded-xl px-4 py-2 text-xs w-64 focus-within:ring-2 ring-brand-200 transition">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>
                        <input name="q" type="search" placeholder="Search orders..." class="bg-transparent outline-none w-full placeholder:text-gray-400">
                    </form>

                    <a href="{{ route('admin.orders.index') }}" class="w-9 h-9 rounded-xl bg-white soft flex items-center justify-center text-gray-500 hover:text-brand-600 transition" aria-label="Orders">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    @endcan

                    {{-- profile dropdown --}}
                    <div class="relative" x-data="{ o:false }" @click.outside="o=false" @keydown.escape.window="o=false">
                        <button @click="o=!o" class="flex items-center gap-2 bg-white soft rounded-xl pl-1.5 pr-3 py-1.5 hover:bg-brand-50 transition">
                            <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-brand-400 to-bloom-400 text-white text-xs font-semibold flex items-center justify-center">{{ $initial }}</span>
                            <span class="hidden sm:block text-sm text-gray-600 max-w-[110px] truncate">{{ $user->name ?? 'Admin' }}</span>
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6" stroke-linecap="round"/></svg>
                        </button>
                        <div x-cloak x-show="o" x-transition.origin.top.right class="absolute right-0 mt-2 w-52 bg-white rounded-2xl soft border border-gray-100 p-2 text-sm z-50">
                            <div class="px-3 py-2 border-b border-gray-100 mb-1">
                                <p class="font-medium truncate">{{ $user->name ?? 'Admin' }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ $user->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-50 text-gray-600">View site</a>
                            @can('settings.view')
                            <a href="{{ route('admin.settings.edit') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-50 text-gray-600">Settings</a>
                            @endcan
                            @can('newsletter.manage')
                            <a href="{{ route('admin.newsletters.index') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-50 text-gray-600">Newsletters</a>
                            @endcan
                            @can('users.edit')
                            <a href="{{ route('admin.permissions.index') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-50 text-gray-600">Manage Permissions</a>
                            @endcan
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="w-full text-left px-3 py-2 rounded-xl hover:bg-red-50 text-red-500">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 md:overflow-y-auto scroll-thin px-4 py-5 md:p-8 pb-10 md:pb-8">
                @if(session('success'))
                    <div class="rise bg-emerald-50 text-emerald-700 border border-emerald-100 px-4 py-3 rounded-2xl text-sm mb-5">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="rise bg-rose-50 text-rose-700 border border-rose-100 px-4 py-3 rounded-2xl text-sm mb-5">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</div>

{{-- FULL-SCREEN MENU (mobile) --}}
<div x-cloak x-show="menu"
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-105"
     class="md:hidden fixed inset-0 z-[60] overflow-y-auto overscroll-contain text-white bg-gradient-to-br from-brand-700 via-brand-600 to-brand-400"
     role="dialog" aria-modal="true" aria-label="Menu">

    <div class="pointer-events-none absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10"></div>
    <div class="pointer-events-none absolute -left-24 bottom-16 w-72 h-72 rounded-full bg-bloom-400/25 blur-2xl"></div>

    <div class="relative min-h-full flex flex-col px-5 pt-[max(1.25rem,env(safe-area-inset-top))] pb-[max(1.5rem,env(safe-area-inset-bottom))]">

        {{-- top bar --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-full bg-white/25 flex items-center justify-center font-semibold">G</span>
                <span class="font-semibold tracking-wide">G. ADMIN</span>
            </div>
            <button type="button" @click="menu = false" aria-label="Close menu"
                    class="w-11 h-11 rounded-full bg-white/15 hover:bg-white/25 backdrop-blur flex items-center justify-center active:scale-90 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>

        {{-- user card --}}
        <div class="rise flex items-center gap-4 rounded-3xl bg-white/15 backdrop-blur px-4 py-4 mb-6" style="--d:.02s">
            <span class="w-12 h-12 rounded-2xl bg-white text-brand-600 text-lg font-semibold flex items-center justify-center shrink-0">{{ $initial }}</span>
            <div class="min-w-0">
                <p class="font-medium truncate">{{ $user->name ?? 'Admin' }}</p>
                <p class="text-xs text-white/70 truncate">{{ $user->email ?? '' }}</p>
            </div>
        </div>

        {{-- main links --}}
        <p class="rise text-[11px] uppercase tracking-[0.2em] text-white/60 mb-3 px-1" style="--d:.05s">Manage</p>
        <nav class="space-y-2.5">
            @foreach($nav as [$label,$route,$pattern,$icon])
                @can($navPermissions[$route])
                    @php $on = request()->routeIs($pattern); @endphp
                    <a href="{{ route($route) }}" @click="menu = false" style="--d:{{ 0.07 + $loop->index * 0.05 }}s"
                       class="rise flex items-center gap-4 rounded-3xl px-4 py-3.5 transition active:scale-[.98]
                              {{ $on ? 'bg-white text-brand-700 shadow-xl shadow-black/10' : 'bg-white/10 hover:bg-white/20 active:bg-white/25' }}">
                        <span class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 {{ $on ? 'bg-brand-50 text-brand-600' : 'bg-white/15' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                        </span>
                        <span class="flex-1 text-lg font-medium">{{ $label }}</span>
                        <svg class="w-5 h-5 {{ $on ? 'text-brand-400' : 'text-white/40' }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m9 6 6 6-6 6"/></svg>
                    </a>
                @endcan
            @endforeach
        </nav>

        {{-- more --}}
        <p class="rise text-[11px] uppercase tracking-[0.2em] text-white/60 mt-7 mb-3 px-1" style="--d:.4s">More</p>
        <div class="rise grid grid-cols-2 gap-2.5" style="--d:.42s">
            <a href="{{ route('home') }}" class="rounded-2xl bg-white/10 hover:bg-white/20 px-4 py-3.5 text-sm font-medium transition active:scale-[.98]">↗ View site</a>
            @can('newsletter.manage')
                <a href="{{ route('admin.newsletters.index') }}" @click="menu = false" class="rounded-2xl bg-white/10 hover:bg-white/20 px-4 py-3.5 text-sm font-medium transition active:scale-[.98]">Newsletters</a>
            @endcan
            @can('users.edit')
                <a href="{{ route('admin.permissions.index') }}" @click="menu = false" class="rounded-2xl bg-white/10 hover:bg-white/20 px-4 py-3.5 text-sm font-medium transition active:scale-[.98]">Permissions</a>
            @endcan
        </div>

        {{-- logout --}}
        <form method="POST" action="{{ route('logout') }}" class="rise mt-auto pt-8" style="--d:.46s">@csrf
            <button class="w-full rounded-2xl bg-white text-rose-600 font-medium py-3.5 text-sm active:scale-[.98] transition">Logout</button>
        </form>
    </div>
</div>

</div>{{-- /menu state wrapper --}}

@stack('scripts')
</body>
</html>