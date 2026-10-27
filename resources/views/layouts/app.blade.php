<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'G. Collection')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight">G.<span class="text-rose-600">Collection</span></a>
        <nav class="flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('home') }}" class="hover:text-rose-600">Home</a>
            <a href="{{ route('shop') }}" class="hover:text-rose-600">Shop</a>
            <a href="{{ route('cart.index') }}" class="relative hover:text-rose-600">
                Cart
                @php $c = count(session('cart', [])); @endphp
                @if($c > 0)
                    <span class="absolute -top-2 -right-4 bg-rose-600 text-white text-xs rounded-full px-1.5">{{ $c }}</span>
                @endif
            </a>
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 font-semibold">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-gray-500 hover:text-rose-600">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-rose-600">Login</a>
            @endauth
        </nav>
    </div>
</header>

@if(session('success'))
    <div class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-red-100 text-red-800 px-4 py-3 rounded">{{ session('error') }}</div>
    </div>
@endif

<main class="min-h-[70vh]">@yield('content')</main>

<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 py-8 text-sm text-center">
        &copy; {{ date('Y') }} G. Collection. All rights reserved.
    </div>
</footer>
</body>
</html>