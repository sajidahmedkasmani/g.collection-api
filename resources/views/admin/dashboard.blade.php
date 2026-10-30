@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
@php
    $cards = [
        'products'       => ['Products',      'from-brand-400 to-brand-600', 'M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 10L4 17V7m8 14V11'],
        'orders'         => ['Orders',        'from-sky-400 to-sky-600',     'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5h6M9 12h6M9 16h4'],
        'pending_orders' => ['Pending',       'from-amber-300 to-orange-400','M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'revenue'        => ['Revenue (Rs.)', 'from-bloom-400 to-bloom-500', 'M12 8c-1.7 0-3 .9-3 2s1.3 2 3 2 3 .9 3 2-1.3 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 11-18 0 9 9 0 0118 0z'],
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
    $byStatus = $recentOrders->groupBy(fn($o) => strtolower($o->status))->map->count();
@endphp

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
    @foreach($cards as $k => [$label,$grad,$icon])
        <div class="bg-white rounded-2xl soft p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $grad }} text-white flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-gray-400">{{ $label }}</p>
                <p class="text-2xl font-semibold mt-0.5 truncate">{{ $k === 'revenue' ? number_format($stats[$k]) : $stats[$k] }}</p>
            </div>
        </div>
    @endforeach
</div>

<div class="grid xl:grid-cols-3 gap-5">
    {{-- RECENT ORDERS --}}
    <div class="xl:col-span-2 bg-white rounded-2xl soft p-5 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold">Recent orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-brand-600 hover:underline">View all</a>
        </div>

        <div class="overflow-x-auto">
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
                    @forelse($recentOrders as $o)
                        <tr class="border-t border-gray-100 hover:bg-brand-50/40 transition">
                            <td class="py-3">
                                <a href="{{ route('admin.orders.show', $o) }}" class="text-brand-600 font-mono text-xs hover:underline">{{ $o->order_number }}</a>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-full bg-brand-100 text-brand-600 text-[11px] font-semibold flex items-center justify-center">{{ strtoupper(substr($o->customer_name,0,1)) }}</span>
                                    <span class="truncate max-w-[140px]">{{ $o->customer_name }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap">Rs. {{ number_format($o->total) }}</td>
                            <td>
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badge[strtolower($o->status)] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($o->status) }}</span>
                            </td>
                            <td class="text-xs text-gray-400 text-right whitespace-nowrap">{{ $o->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-gray-400">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SIDE COLUMN --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl soft p-5 md:p-6">
            <h2 class="font-semibold mb-4">Order status</h2>
            @forelse($byStatus as $status => $count)
                <div class="flex items-center justify-between py-2 text-sm">
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badge[$status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($status) }}</span>
                    <span class="text-gray-500">{{ $count }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Nothing to show yet.</p>
            @endforelse
        </div>

        <div class="rounded-2xl p-6 text-white bg-gradient-to-br from-brand-500 to-brand-400 soft">
            <p class="text-lg font-semibold mb-1">Quick actions</p>
            <p class="text-xs text-white/80 mb-5">Jump straight to what you need.</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn-bloom text-xs rounded-lg px-4 py-2 font-medium">Products</a>
                <a href="{{ route('admin.orders.index') }}" class="bg-white/20 hover:bg-white/30 transition text-xs rounded-lg px-4 py-2 font-medium">Orders</a>
                <a href="{{ route('home') }}" class="bg-white/20 hover:bg-white/30 transition text-xs rounded-lg px-4 py-2 font-medium">View site</a>
            </div>
        </div>
    </div>
</div>
@endsection