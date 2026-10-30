<!DOCTYPE html>
<html lang="en">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
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
    <style>
        [x-cloak]{display:none!important}
        .rail{background:linear-gradient(180deg,#7B47D8 0%,#9A72E6 55%,#C9B2F5 100%)}
        .btn-bloom{background:linear-gradient(90deg,#FF7BA9,#F9528A);box-shadow:0 8px 18px -6px rgba(249,82,138,.55)}
        .soft{box-shadow:0 6px 24px -10px rgba(95,50,168,.18)}
        body{background:
            radial-gradient(900px 500px at 0% 0%, #F7F4FC 0%, transparent 60%),
            radial-gradient(800px 500px at 100% 100%, #E3D9F6 0%, transparent 60%), #EFEBF7}
        .scroll-thin::-webkit-scrollbar{width:6px}
        .scroll-thin::-webkit-scrollbar-thumb{background:#D3C2F7;border-radius:9px}
    </style>
</head>
<body class="font-sans text-ink antialiased">
@php
    $nav = [
        ['Dashboard', 'admin.dashboard',        'admin.dashboard',  'M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10'],
        ['Products',  'admin.products.index',   'admin.products.*', 'M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 10L4 17V7m8 14V11'],
        ['Orders',    'admin.orders.index',     'admin.orders.*',   'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5h6M9 12h6M9 16h4'],
        ['Settings',  'admin.settings.edit',    'admin.settings.*', 'M10.3 3.3a1.7 1.7 0 013.4 0l.2.9a1.7 1.7 0 002.5 1.1l.8-.5a1.7 1.7 0 012.4 2.4l-.5.8a1.7 1.7 0 001.1 2.5l.9.2a1.7 1.7 0 010 3.4l-.9.2a1.7 1.7 0 00-1.1 2.5l.5.8a1.7 1.7 0 01-2.4 2.4l-.8-.5a1.7 1.7 0 00-2.5 1.1l-.2.9a1.7 1.7 0 01-3.4 0l-.2-.9a1.7 1.7 0 00-2.5-1.1l-.8.5a1.7 1.7 0 01-2.4-2.4l.5-.8a1.7 1.7 0 00-1.1-2.5l-.9-.2a1.7 1.7 0 010-3.4l.9-.2a1.7 1.7 0 001.1-2.5l-.5-.8A1.7 1.7 0 016.8 4.8l.8.5a1.7 1.7 0 002.5-1.1l.2-.9zM12 9a3 3 0 100 6 3 3 0 000-6z'],
    ];
    $user = auth()->user();
    $initial = strtoupper(substr($user->name ?? 'A', 0, 1));
@endphp

<div class="min-h-screen p-0 md:p-6" x-data="{ drawer:false }">
    <div class="bg-white/60 md:rounded-[28px] shadow-[0_30px_80px_-30px_rgba(95,50,168,.35)] overflow-hidden flex min-h-screen md:min-h-0 md:h-[calc(100vh-3rem)]">

        {{-- ICON RAIL (desktop) --}}
        <div class="rail hidden md:flex w-[72px] shrink-0 flex-col items-center py-5 gap-3 text-white/80">
            <div class="w-10 h-10 flex items-center justify-center text-white mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
            </div>
            @foreach($nav as [$label,$route,$pattern,$icon])
                <a href="{{ route($route) }}" title="{{ $label }}"
                   class="w-11 h-11 rounded-xl flex items-center justify-center transition {{ request()->routeIs($pattern) ? 'bg-white/25 text-white shadow-inner' : 'hover:bg-white/15' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                </a>
            @endforeach
            <div class="mt-auto">
                <div class="w-10 h-10 rounded-full bg-white text-brand-600 font-semibold flex items-center justify-center ring-4 ring-white/30">{{ $initial }}</div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <aside class="fixed md:static inset-y-0 left-0 z-40 w-64 md:w-[230px] shrink-0 bg-white flex flex-col px-5 py-6 transition-transform md:translate-x-0"
               :class="drawer ? 'translate-x-0' : '-translate-x-full'" x-cloak>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 mb-8 px-1">
                <span class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-bloom-400"></span>
                <span class="font-semibold tracking-wide">G. ADMIN</span>
            </a>

            @if(Route::has('admin.products.create'))
                <a href="{{ route('admin.products.create') }}" class="btn-bloom text-white rounded-xl py-3 px-4 flex items-center justify-center gap-2 text-sm font-medium mb-7 hover:opacity-90 transition">
                    <span class="text-lg leading-none">+</span> Add Product
                </a>
            @else
                <a href="{{ route('admin.products.index') }}" class="btn-bloom text-white rounded-xl py-3 px-4 flex items-center justify-center gap-2 text-sm font-medium mb-7 hover:opacity-90 transition">
                    <span class="text-lg leading-none">+</span> Products
                </a>
            @endif

            <p class="text-[11px] text-gray-400 mb-3 px-1">Manage</p>
            <nav class="space-y-1 text-sm flex-1">
                @foreach($nav as [$label,$route,$pattern,$icon])
                    @php $on = request()->routeIs($pattern); @endphp
                    <a href="{{ route($route) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ $on ? 'bg-brand-50 text-brand-600 font-medium' : 'text-gray-500 hover:bg-gray-50 hover:text-ink' }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="pt-5 border-t border-gray-100 text-sm space-y-1">
                <p class="text-[11px] text-gray-400 mb-2 px-1">Store</p>
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-ink transition">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M10 19l-7-7 7-7M3 12h18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Back to site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-red-50 hover:text-red-600 transition">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M15 12H3m0 0l4-4m-4 4l4 4M9 5V4a2 2 0 012-2h8a2 2 0 012 2v16a2 2 0 01-2 2h-8a2 2 0 01-2-2v-1" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        <div x-show="drawer" x-cloak @click="drawer=false" class="fixed inset-0 bg-black/30 z-30 md:hidden"></div>

        {{-- MAIN --}}
        <div class="flex-1 min-w-0 flex flex-col bg-lav/70">
            <header class="h-[72px] shrink-0 px-5 md:px-8 flex items-center justify-between border-b border-white/70">
                <div class="flex items-center gap-3">
                    <button class="md:hidden w-9 h-9 rounded-lg bg-white soft flex items-center justify-center" @click="drawer=true" aria-label="Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <h1 class="text-xl font-semibold">@yield('title')</h1>
                </div>
                <div class="flex items-center gap-4 text-gray-400">
                    <div class="hidden lg:flex items-center gap-2 bg-white/80 rounded-xl px-4 py-2 text-xs w-64">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>
                        <span>Search here...</span>
                    </div>
                    <span class="relative hidden sm:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-bloom-500"></span>
                    </span>
                    <div class="flex items-center gap-2 text-ink">
                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-bloom-400 text-white text-xs font-semibold flex items-center justify-center">{{ $initial }}</span>
                        <span class="hidden sm:block text-sm text-gray-600">{{ $user->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto scroll-thin p-5 md:p-8">
                @if(session('success'))
                    <div class="bg-emerald-50 text-emerald-700 border border-emerald-100 px-4 py-3 rounded-xl text-sm mb-5">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-rose-50 text-rose-700 border border-rose-100 px-4 py-3 rounded-xl text-sm mb-5">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</div>
</body>
</html>