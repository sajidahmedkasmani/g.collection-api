<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin — @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">
    <aside class="w-64 bg-gray-900 text-gray-200 p-4 flex flex-col">
        <h2 class="text-xl font-bold mb-6 text-white">G. Admin</h2>
        <nav class="space-y-1 text-sm flex-1">
            @php $active = request()->routeIs(...); @endphp
            <a href="{{ route('admin.dashboard') }}"
               class="block px-3 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800' }}">Dashboard</a>
            <a href="{{ route('admin.products.index') }}"
               class="block px-3 py-2 rounded {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800' }}">Products</a>
            <a href="{{ route('admin.orders.index') }}"
               class="block px-3 py-2 rounded {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800' }}">Orders</a>
            <a href="{{ route('admin.settings.edit') }}"
               class="block px-3 py-2 rounded {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white' : 'hover:bg-gray-800' }}">Settings</a>
        </nav>
        <div class="text-sm space-y-1 pt-4 border-t border-gray-700">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded hover:bg-gray-800">← Back to Site</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="block w-full text-left px-3 py-2 rounded hover:bg-red-700">Logout</button>
            </form>
        </div>
    </aside>
    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-6">@yield('title')</h1>
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>