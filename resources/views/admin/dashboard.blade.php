@extends('admin.admin_layout.master')
@section('title', 'Dashboard')

@section('content')
@php
    $hour = now('Asia/Karachi')->hour;
    $greet = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $name = explode(' ', trim(auth()->user()->name ?? 'Admin'))[0];

    $cards = [
        'products'       => ['Products',      'from-brand-400 to-brand-600',  'bg-brand-100',  'M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 10L4 17V7m8 14V11', 'admin.products.index'],
        'orders'         => ['Orders',        'from-sky-400 to-sky-600',      'bg-sky-100',    'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5h6M9 12h6M9 16h4', 'admin.orders.index'],
        'pending_orders' => ['Pending',       'from-amber-300 to-orange-400', 'bg-amber-100',  'M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z', 'admin.orders.index'],
        'revenue'        => ['Revenue (Rs.)', 'from-bloom-400 to-bloom-500',  'bg-pink-100',   'M12 8c-1.7 0-3 .9-3 2s1.3 2 3 2 3 .9 3 2-1.3 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z', 'admin.orders.index'],
    ];

    $badge = [
        'pending'    => 'bg-amber-50 text-amber-600',
        'processing' => 'bg-sky-50 text-sky-600',
        'confirmed'  => 'bg-sky-50 text-sky-600',
        'shipped'    => 'bg-brand-50 text-brand-600',
        'delivered'  => 'bg-emerald-50 text-emerald-600',
        'completed'  => 'bg-emerald-50 text-emerald-600',
        'cancelled'  => 'bg-rose-50 text-rose-600',
    ];
    $bar = [
        'pending'    => 'bg-amber-400',
        'processing' => 'bg-sky-400',
        'confirmed'  => 'bg-sky-400',
        'shipped'    => 'bg-brand-400',
        'delivered'  => 'bg-emerald-400',
        'completed'  => 'bg-emerald-400',
        'cancelled'  => 'bg-rose-400',
    ];

    $orders   = $recentOrders->values();
    $count    = $orders->count();
    $byStatus = $orders->groupBy(fn($o) => strtolower($o->status))->map->count();
    $chart    = $orders->reverse()->values();
    $max      = max((float) $chart->max('total'), 1);
    $pending  = (int) ($stats['pending_orders'] ?? 0);
@endphp

{{-- HERO --}}
<div class="rise relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-500 to-brand-400 text-white p-6 md:p-8 mb-5 md:mb-6">
    <div class="absolute -right-10 -top-12 w-56 h-56 rounded-full bg-white/10"></div>
    <div class="absolute right-16 -bottom-16 w-40 h-40 rounded-full bg-bloom-400/30"></div>
    <div class="relative max-w-xl">
        <p class="text-xs text-white/70 mb-1">{{ now('Asia/Karachi')->format('l, d F Y') }}</p>
        <h2 class="text-2xl md:text-3xl font-semibold leading-tight">{{ $greet }}, {{ $name }} 👋</h2>
        <p class="text-sm text-white/80 mt-2">
            @if($pending > 0)
                You have <span class="font-semibold text-white">{{ $pending }} pending {{ \Illuminate\Support\Str::plural('order', $pending) }}</span> waiting for you.
            @else
                You're all caught up. No pending orders right now.
            @endif
        </p>
        <div class="flex flex-wrap gap-2 mt-5">
            <a href="{{ route('admin.orders.index') }}" class="btn-bloom text-xs font-medium rounded-xl px-5 py-2.5 hover:opacity-90 transition">View orders</a>
            <a href="{{ route('admin.products.index') }}" class="bg-white/20 hover:bg-white/30 transition text-xs font-medium rounded-xl px-5 py-2.5">Manage products</a>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-3 md:gap-5 mb-5 md:mb-6">
    @foreach($cards as $k => [$label,$grad,$tint,$icon,$route])
        <a href="{{ route($route) }}" class="rise lift relative overflow-hidden bg-white rounded-2xl soft p-4 md:p-5 block" style="--d:{{ 0.06 * ($loop->index + 1) }}s">
            <div class="absolute -right-5 -top-5 w-20 h-20 rounded-full {{ $tint }} opacity-70"></div>
            <div class="relative">
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br {{ $grad }} text-white flex items-center justify-center shadow-md mb-3">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                </div>
                <p class="text-[11px] md:text-xs text-gray-400">{{ $label }}</p>
                <p class="text-xl md:text-2xl font-semibold mt-0.5 truncate"
                   x-data="counter({{ (int) ($stats[$k] ?? 0) }})"
                   x-text="v.toLocaleString()">{{ number_format($stats[$k] ?? 0) }}</p>
            </div>
        </a>
    @endforeach
</div>

<div class="grid xl:grid-cols-3 gap-5 md:gap-6">

    {{-- LEFT: chart + orders --}}
    <div class="xl:col-span-2 space-y-5 md:space-y-6 min-w-0">

        {{-- CHART --}}
        <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.3s">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-semibold">Recent order totals</h2>
                    <p class="text-[11px] text-gray-400">Last {{ $count }} {{ \Illuminate\Support\Str::plural('order', $count) }}, oldest to newest</p>
                </div>
                <span class="text-[11px] bg-brand-50 text-brand-600 rounded-full px-3 py-1">Rs.</span>
            </div>

            @if($count)
                <div class="flex items-end gap-2 md:gap-3 h-44">
                    @foreach($chart as $i => $o)
                        @php $h = max(8, round($o->total / $max * 100)); @endphp
                        <div class="group relative flex-1 h-full flex flex-col justify-end items-center min-w-0">
                            <span class="absolute -top-1 left-1/2 -translate-x-1/2 -translate-y-full text-[10px] bg-ink text-white rounded-md px-2 py-1 whitespace-nowrap opacity-0 group-hover:opacity-100 transition pointer-events-none z-10">Rs. {{ number_format($o->total) }}</span>
                            <div class="grow w-full max-w-[44px] rounded-t-xl bg-gradient-to-t from-brand-500 to-bloom-400 group-hover:from-brand-600 group-hover:to-bloom-500 transition-colors"
                                 style="height:{{ $h }}%; --d:{{ 0.35 + $i * 0.06 }}s"></div>
                        </div>
                    @endforeach
                </div>
                <div class="flex gap-2 md:gap-3 mt-2">
                    @foreach($chart as $o)
                        <p class="flex-1 text-center text-[10px] text-gray-400 truncate">{{ $o->created_at->format('d M') }}</p>
                    @endforeach
                </div>
            @else
                <div class="h-44 flex flex-col items-center justify-center text-gray-400 text-sm">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-500 flex items-center justify-center mb-2 text-xl">📈</div>
                    The chart appears once orders come in.
                </div>
            @endif
        </div>

        {{-- ORDERS --}}
        <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.4s">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold">Recent orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-brand-600 hover:underline">View all →</a>
            </div>

            {{-- mobile: cards --}}
            <div class="md:hidden space-y-3">
                @forelse($orders as $o)
                    @php $st = strtolower($o->status); @endphp
                    <a href="{{ route('admin.orders.show', $o) }}" class="block rounded-2xl border border-gray-100 p-4 active:bg-brand-50 transition">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-9 h-9 rounded-full bg-brand-100 text-brand-600 text-xs font-semibold flex items-center justify-center shrink-0">{{ strtoupper(substr($o->customer_name, 0, 1)) }}</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $o->customer_name }}</p>
                                    <p class="text-[11px] font-mono text-brand-600 truncate">{{ $o->order_number }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-medium shrink-0 {{ $badge[$st] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($o->status) }}</span>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 text-xs">
                            <span class="font-semibold text-sm">Rs. {{ number_format($o->total) }}</span>
                            <span class="text-gray-400">{{ $o->created_at->format('d M Y') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="py-10 text-center text-gray-400 text-sm">No orders yet.</div>
                @endforelse
            </div>

            {{-- desktop: table --}}
            <div class="hidden md:block overflow-x-auto scroll-thin">
                <table class="w-full text-sm">
                    <thead class="text-left text-[11px] text-gray-400">
                        <tr>
                            <th class="py-2 font-medium">Order #</th>
                            <th class="font-medium">Customer</th>
                            <th class="font-medium">Total</th>
                            <th class="font-medium">Status</th>
                            <th class="font-medium text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $o)
                            @php $st = strtolower($o->status); @endphp
                            <tr class="border-t border-gray-100 hover:bg-brand-50/50 transition">
                                <td class="py-3">
                                    <a href="{{ route('admin.orders.show', $o) }}" class="text-brand-600 font-mono text-xs hover:underline">{{ $o->order_number }}</a>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-full bg-brand-100 text-brand-600 text-[11px] font-semibold flex items-center justify-center">{{ strtoupper(substr($o->customer_name, 0, 1)) }}</span>
                                        <span class="truncate max-w-[160px]">{{ $o->customer_name }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap font-medium">Rs. {{ number_format($o->total) }}</td>
                                <td><span class="px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badge[$st] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($o->status) }}</span></td>
                                <td class="text-xs text-gray-400 text-right whitespace-nowrap">{{ $o->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-10 text-center text-gray-400">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="space-y-5 md:space-y-6">
        <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.45s">
            <h2 class="font-semibold mb-4">Order status</h2>
            @forelse($byStatus as $status => $n)
                @php $pct = $count ? round($n / $count * 100) : 0; @endphp
                <div class="mb-4 last:mb-0">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badge[$status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($status) }}</span>
                        <span class="text-gray-400">{{ $n }} · {{ $pct }}%</span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full {{ $bar[$status] ?? 'bg-gray-400' }}" style="width:{{ $pct }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400">Nothing to show yet.</p>
            @endforelse
        </div>

        <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.5s">
            <h2 class="font-semibold mb-4">Quick actions</h2>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <a href="{{ Route::has('admin.products.create') ? route('admin.products.create') : route('admin.products.index') }}" class="lift rounded-2xl bg-brand-50 text-brand-600 p-4 font-medium">
                    <span class="block text-xl mb-1">＋</span>Add product
                </a>
                <a href="{{ route('admin.orders.index') }}" class="lift rounded-2xl bg-amber-50 text-amber-600 p-4 font-medium">
                    <span class="block text-xl mb-1">🧾</span>Orders
                </a>
                <a href="{{ route('admin.settings.edit') }}" class="lift rounded-2xl bg-sky-50 text-sky-600 p-4 font-medium">
                    <span class="block text-xl mb-1">⚙️</span>Settings
                </a>
                <a href="{{ route('home') }}" class="lift rounded-2xl bg-pink-50 text-bloom-500 p-4 font-medium">
                    <span class="block text-xl mb-1">🛍️</span>View site
                </a>
            </div>
        </div>
    </div>
</div>
@endsection