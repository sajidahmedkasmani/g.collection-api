@extends('admin.layout')
@section('title', 'Orders')

@section('content')
@php
    if (method_exists($orders, 'withQueryString')) { $orders = $orders->withQueryString(); }

    $statuses = ['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'shipped' => 'Shipped', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
    $current  = (string) request('status');
    $search   = trim((string) request('q'));
    $total    = method_exists($orders, 'total') ? $orders->total() : $orders->count();
    $url      = fn($s) => route('admin.orders.index', array_filter(['status' => $s, 'q' => $search]));

    $badge = [
        'pending'    => ['bg-amber-50 text-amber-600',   'bg-amber-400'],
        'processing' => ['bg-sky-50 text-sky-600',       'bg-sky-400'],
        'confirmed'  => ['bg-sky-50 text-sky-600',       'bg-sky-400'],
        'shipped'    => ['bg-brand-50 text-brand-600',   'bg-brand-400'],
        'delivered'  => ['bg-emerald-50 text-emerald-600','bg-emerald-400'],
        'completed'  => ['bg-emerald-50 text-emerald-600','bg-emerald-400'],
        'cancelled'  => ['bg-rose-50 text-rose-600',     'bg-rose-400'],
    ];
    $waLink = function ($phone) {
        $d = preg_replace('/\D+/', '', (string) $phone);
        if ($d === '') return null;
        if (str_starts_with($d, '0')) $d = '92' . substr($d, 1);
        return 'https://wa.me/' . $d;
    };
    $cols = 'grid-cols-[130px_minmax(0,2fr)_110px_90px_130px_100px_80px]';
@endphp

<div class="space-y-5">

    {{-- ============ TOOLBAR ============ --}}
    <div class="rise bg-white rounded-3xl soft p-4 md:p-5 space-y-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2 bg-lav/70 rounded-2xl pl-4 pr-1.5 py-1.5 focus-within:ring-4 ring-brand-100 transition">
            @if($current !== '')<input type="hidden" name="status" value="{{ $current }}">@endif
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>
            <input type="search" name="q" value="{{ $search }}" placeholder="Search by order #, name or phone..."
                   class="flex-1 min-w-0 bg-transparent text-sm py-2.5 focus:outline-none placeholder:text-gray-400">
            @if($search)
                <a href="{{ route('admin.orders.index', array_filter(['status' => $current])) }}" class="text-xs text-gray-400 hover:text-gray-600 px-2" title="Clear search">✕</a>
            @endif
            <button class="bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium rounded-xl px-5 py-2.5 transition">Search</button>
        </form>

        <div class="flex items-center justify-between gap-3">
            <div class="flex gap-2 overflow-x-auto -mx-1 px-1 flex-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @foreach($statuses as $val => $label)
                    @php $on = $current === (string) $val; @endphp
                    <a href="{{ $url($val ?: null) }}"
                       class="shrink-0 text-xs rounded-xl px-4 py-2.5 transition {{ $on ? 'bg-brand-600 text-white font-medium shadow-md shadow-brand-600/25' : 'bg-lav/70 text-gray-500 hover:text-ink hover:bg-brand-50' }}">{{ $label }}</a>
                @endforeach
            </div>
            <p class="hidden sm:block text-xs text-gray-400 shrink-0">{{ $total }} {{ \Illuminate\Support\Str::plural('order', $total) }}</p>
        </div>
    </div>

    {{-- ============ MOBILE: CARDS ============ --}}
    <div class="md:hidden space-y-3">
        @forelse($orders as $o)
            @php
                $st = strtolower($o->status);
                [$sb, $sd] = $badge[$st] ?? ['bg-gray-100 text-gray-600', 'bg-gray-400'];
                $wa = $waLink($o->phone);
            @endphp
            <div class="rise bg-white rounded-3xl soft p-4" style="--d:{{ min($loop->index, 8) * 0.04 }}s">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <a href="{{ route('admin.orders.show', $o) }}" class="font-mono text-xs text-brand-600">{{ $o->order_number }}</a>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium {{ $sb }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $sd }}"></span>{{ ucfirst($o->status) }}
                    </span>
                </div>

                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-brand-100 text-brand-600 text-sm font-semibold flex items-center justify-center shrink-0">{{ strtoupper(substr($o->customer_name, 0, 1)) }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-sm truncate">{{ $o->customer_name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $o->phone }}</p>
                    </div>
                    @if($o->phone)
                        <div class="flex gap-1.5 shrink-0">
                            <a href="tel:{{ preg_replace('/\s+/', '', $o->phone) }}" aria-label="Call" class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.500 1.500a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/></svg>
                            </a>
                            @if($wa)
                                <a href="{{ $wa }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 20l1.300-4.200A8.500 8.500 0 1112 20.500a8.400 8.400 0 01-4-1L4 20z"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between gap-3 mt-4 pt-4 border-t border-dashed border-gray-200">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400">Total</p>
                        <p class="text-sm font-semibold">Rs. {{ number_format($o->total) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400">Payment</p>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $o->payment_method === 'cod' ? 'bg-amber-50 text-amber-700' : 'bg-sky-50 text-sky-600' }}">{{ $o->payment_method === 'cod' ? 'COD' : 'Bank' }}</span>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400">Date</p>
                        <p class="text-xs text-gray-500">{{ $o->created_at->format('d M Y') }}</p>
                    </div>
                    <a href="{{ route('admin.orders.show', $o) }}" class="bg-brand-600 text-white text-xs font-medium rounded-xl px-4 py-2.5 active:scale-95 transition">View</a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl soft py-14 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 text-brand-500 flex items-center justify-center text-2xl mb-3">🧾</div>
                <p class="font-medium">No orders found</p>
                <p class="text-xs text-gray-400 mt-1">Orders will appear here as they come in.</p>
            </div>
        @endforelse
    </div>

    {{-- ============ DESKTOP: TABLE ============ --}}
    <div class="hidden md:block rise bg-white rounded-3xl soft overflow-hidden" style="--d:.1s">
        <div class="grid {{ $cols }} gap-x-3 items-center px-5 py-3 bg-brand-50/60 text-[11px] uppercase tracking-wider text-gray-400">
            <span>Order #</span><span>Customer</span><span>Total</span><span>Payment</span><span>Status</span><span>Date</span><span class="text-right">Action</span>
        </div>

        @forelse($orders as $o)
            @php
                $st = strtolower($o->status);
                [$sb, $sd] = $badge[$st] ?? ['bg-gray-100 text-gray-600', 'bg-gray-400'];
                $wa = $waLink($o->phone);
            @endphp
            <div class="grid {{ $cols }} gap-x-3 items-center px-5 py-3.5 border-t border-gray-100 hover:bg-brand-50/30 transition text-sm">
                <a href="{{ route('admin.orders.show', $o) }}" class="font-mono text-xs text-brand-600 hover:underline truncate">{{ $o->order_number }}</a>

                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-9 h-9 rounded-full bg-brand-100 text-brand-600 text-xs font-semibold flex items-center justify-center shrink-0">{{ strtoupper(substr($o->customer_name, 0, 1)) }}</span>
                    <div class="min-w-0">
                        <p class="font-medium truncate">{{ $o->customer_name }}</p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ $o->phone }}
                            @if($wa)<a href="{{ $wa }}" target="_blank" rel="noopener" class="text-emerald-600 hover:underline ml-1">WhatsApp</a>@endif
                        </p>
                    </div>
                </div>

                <span class="font-semibold whitespace-nowrap">Rs. {{ number_format($o->total) }}</span>

                <span><span class="px-2.5 py-1 rounded-full text-[11px] font-medium {{ $o->payment_method === 'cod' ? 'bg-amber-50 text-amber-700' : 'bg-sky-50 text-sky-600' }}">{{ $o->payment_method === 'cod' ? 'COD' : 'Bank' }}</span></span>

                <span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium {{ $sb }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $sd }}"></span>{{ ucfirst($o->status) }}
                    </span>
                </span>

                <span class="text-xs text-gray-400 whitespace-nowrap">{{ $o->created_at->format('d M Y') }}</span>

                <span class="text-right">
                    <a href="{{ route('admin.orders.show', $o) }}" class="inline-block bg-brand-50 text-brand-600 hover:bg-brand-600 hover:text-white text-xs font-medium rounded-xl px-4 py-2 transition">View</a>
                </span>
            </div>
        @empty
            <div class="py-16 text-center border-t border-gray-100">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 text-brand-500 flex items-center justify-center text-2xl mb-3">🧾</div>
                <p class="font-medium">No orders found</p>
                <p class="text-xs text-gray-400 mt-1">@if($search || $current) Try clearing the filters. @else Orders will appear here as they come in. @endif</p>
                @if($search || $current)<a href="{{ route('admin.orders.index') }}" class="inline-block mt-4 text-xs text-brand-600 hover:underline">Clear filters</a>@endif
            </div>
        @endforelse
    </div>

    {{-- ============ PAGINATION ============ --}}
    @if(method_exists($orders, 'hasPages') && $orders->hasPages())
        <div class="flex items-center justify-between gap-3 flex-wrap pb-2">
            <p class="text-xs text-gray-400">Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</p>
            <div class="flex items-center gap-2 text-xs">
                @if($orders->onFirstPage())
                    <span class="px-4 py-2.5 rounded-xl bg-white text-gray-300">← Prev</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="px-4 py-2.5 rounded-xl bg-white soft hover:bg-brand-600 hover:text-white transition">← Prev</a>
                @endif
                <span class="px-3 text-gray-500">Page {{ $orders->currentPage() }} / {{ $orders->lastPage() }}</span>
                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="px-4 py-2.5 rounded-xl bg-white soft hover:bg-brand-600 hover:text-white transition">Next →</a>
                @else
                    <span class="px-4 py-2.5 rounded-xl bg-white text-gray-300">Next →</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection