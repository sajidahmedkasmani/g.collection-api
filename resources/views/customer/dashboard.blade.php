@extends('frontend.layout.master')
@section('title', 'My Account')

@section('content')
@php
    $cust       = auth('customer')->user();
    $orders     = \App\Models\Order::where('customer_id', $cust->id)->latest()->take(5)->get();
    $orderCount = \App\Models\Order::where('customer_id', $cust->id)->count();
    $pill = [
        'pending'    => 'bg-amber-50 text-amber-800',
        'processing' => 'bg-blue-50 text-blue-800',
        'shipped'    => 'bg-indigo-50 text-indigo-800',
        'delivered'  => 'bg-green-50 text-green-800',
        'completed'  => 'bg-green-50 text-green-800',
        'cancelled'  => 'bg-red-50 text-red-800',
    ];
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 md:py-16">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-10">
        <div class="flex items-center gap-4 min-w-0">
            <div class="w-14 h-[72px] arch bg-plum text-cream font-serif text-2xl flex items-end justify-center pb-2 shrink-0">
                {{ mb_strtoupper(mb_substr($cust->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm text-muted">Welcome back</p>
                <h1 class="font-serif text-3xl md:text-4xl leading-tight truncate">{{ $cust->name }}</h1>
            </div>
        </div>
        <form method="POST" action="{{ route('customer.logout') }}" class="shrink-0">
            @csrf
            <button class="text-sm border border-black/15 rounded-full px-5 py-2 hover:border-plum hover:text-plum transition">Log out</button>
        </form>
    </div>

    <div class="grid md:grid-cols-[320px_1fr] gap-6 md:gap-8 items-start">

        {{-- Profile --}}
        <section class="bg-white rounded-2xl border border-black/5 p-6">
            <h2 class="font-serif text-2xl mb-5">Profile</h2>
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="text-muted text-xs mb-0.5">Name</dt>
                    <dd class="font-medium">{{ $cust->name }}</dd>
                </div>
                <div>
                    <dt class="text-muted text-xs mb-0.5">Email</dt>
                    <dd class="font-medium break-all">{{ $cust->email }}</dd>
                </div>
                <div>
                    <dt class="text-muted text-xs mb-0.5">Phone</dt>
                    <dd class="font-medium">{{ $cust->phone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-muted text-xs mb-0.5">City</dt>
                    <dd class="font-medium">{{ $cust->city ?? '—' }}</dd>
                </div>
            </dl>
            <a href="{{ route('customer.profile.edit') }}" class="block text-center mt-6 border border-black/15 rounded-full py-2.5 text-sm hover:border-plum hover:text-plum transition">Edit profile</a>
        </section>

        {{-- Orders --}}
        <section id="orders" class="bg-white rounded-2xl border border-black/5 p-6 scroll-mt-28">
            <div class="flex items-baseline justify-between mb-5">
                <h2 class="font-serif text-2xl">My orders</h2>
                @if($orderCount > 0)
                    <span class="text-xs text-muted">Latest {{ $orders->count() }} of {{ $orderCount }}</span>
                @endif
            </div>

            @forelse($orders as $order)
                @php $key = strtolower((string) $order->status); @endphp
                <div class="flex items-center justify-between gap-4 py-4 border-t border-black/10 first:border-0 first:pt-0">
                    <div class="min-w-0">
                        <p class="font-medium">#{{ $order->order_number }}</p>
                        <p class="text-xs text-muted mt-0.5">{{ optional($order->created_at)->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-4 shrink-0">
                        <span class="text-sm font-semibold">Rs. {{ number_format($order->total) }}</span>
                        <span class="text-xs px-3 py-1 rounded-full capitalize {{ $pill[$key] ?? 'bg-gray-100 text-gray-700' }}">{{ $order->status }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <div class="mx-auto w-20 h-28 arch bg-blush"></div>
                    <p class="font-serif text-xl mt-5">No orders yet</p>
                    <p class="text-sm text-muted mt-1">When you place an order, it will show up here.</p>
                    <a href="{{ route('shop') }}" class="inline-block mt-5 bg-plum text-cream px-7 py-3 rounded-full text-sm font-medium hover:bg-ink transition">Start shopping</a>
                </div>
            @endforelse
        </section>
    </div>
</div>
@endsection