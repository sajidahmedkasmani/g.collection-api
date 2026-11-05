@extends('admin.admin_layout.master')
@section('title', 'Order ' . $order->order_number)

@section('content')
@php
    $st   = strtolower($order->status);
    $meta = [
        'pending'   => ['bg-amber-50 text-amber-600',    'bg-amber-400'],
        'confirmed' => ['bg-sky-50 text-sky-600',        'bg-sky-400'],
        'shipped'   => ['bg-brand-50 text-brand-600',    'bg-brand-400'],
        'completed' => ['bg-emerald-50 text-emerald-600','bg-emerald-400'],
        'cancelled' => ['bg-rose-50 text-rose-600',      'bg-rose-400'],
    ];
    [$badge, $dot] = $meta[$st] ?? ['bg-gray-100 text-gray-600', 'bg-gray-400'];

    $flow   = ['pending', 'confirmed', 'shipped', 'completed'];
    $step   = array_search($st, $flow);
    $items  = $order->items;
    $qty    = $items->sum('quantity');

    $digits = preg_replace('/\D+/', '', (string) $order->phone);
    if ($digits !== '' && str_starts_with($digits, '0')) { $digits = '92' . substr($digits, 1); }
    $wa     = $digits ? 'https://wa.me/' . $digits : null;
    $isCod  = $order->payment_method === 'cod';
    $fullAddress = trim(($order->address ?? '') . ($order->city ? ', ' . $order->city : ''));
@endphp

<style>
    @media print {
        aside, header, nav, .rail, .print\:hidden { display: none !important; }
        body { background: #fff !important; }
        .soft { box-shadow: none !important; border: 1px solid #e5e7eb; }
        main { overflow: visible !important; padding: 0 !important; }
    }
</style>

<div class="space-y-5 md:space-y-6">

    {{-- ============ HEADER ============ --}}
    <div class="rise relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-500 to-brand-400 text-white p-5 md:p-7">
        <div class="absolute -right-8 -top-10 w-44 h-44 rounded-full bg-white/10"></div>
        <div class="absolute right-24 -bottom-14 w-32 h-32 rounded-full bg-bloom-400/30"></div>

        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div class="min-w-0">
                <a href="{{ route('admin.orders.index') }}" class="print:hidden inline-flex items-center gap-1.5 text-xs text-white/70 hover:text-white mb-3 transition">← All orders</a>
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="font-mono text-xl md:text-2xl font-semibold tracking-wide break-all">{{ $order->order_number }}</h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-medium {{ $badge }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>{{ ucfirst($order->status) }}
                    </span>
                </div>
                <p class="text-xs text-white/75 mt-2">Placed {{ $order->created_at->format('d M Y, h:i A') }} · {{ $qty }} {{ \Illuminate\Support\Str::plural('item', $qty) }} · Rs. {{ number_format($order->total) }}</p>
            </div>

            <div class="print:hidden flex flex-wrap gap-2">
                @if($wa)
                    <a href="{{ $wa }}" target="_blank" rel="noopener" class="bg-white text-brand-700 hover:bg-brand-50 text-xs font-medium rounded-xl px-4 py-2.5 transition">WhatsApp</a>
                @endif
                @if($order->phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $order->phone) }}" class="bg-white/20 hover:bg-white/30 text-xs font-medium rounded-xl px-4 py-2.5 transition">Call</a>
                @endif
                <button type="button" onclick="window.print()" class="bg-white/20 hover:bg-white/30 text-xs font-medium rounded-xl px-4 py-2.5 transition">Print</button>
            </div>
        </div>
    </div>

    {{-- ============ PROGRESS ============ --}}
    <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.05s">
        @if($st === 'cancelled')
            <div class="flex items-center gap-3 text-rose-600">
                <span class="w-10 h-10 rounded-2xl bg-rose-50 flex items-center justify-center text-lg">✕</span>
                <div>
                    <p class="font-semibold text-sm">This order was cancelled</p>
                    <p class="text-xs text-gray-400">No further steps will happen unless you change the status.</p>
                </div>
            </div>
        @else
            <ol class="grid grid-cols-4 gap-2">
                @foreach($flow as $i => $f)
                    @php $done = $step !== false && $i <= $step; $now = $step === $i; @endphp
                    <li class="relative text-center">
                        @if(! $loop->last)
                            <span class="absolute top-4 left-1/2 w-full h-0.5 {{ $step !== false && $i < $step ? 'bg-brand-500' : 'bg-gray-100' }}"></span>
                        @endif
                        <span class="relative z-10 mx-auto w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold transition
                            {{ $done ? 'bg-brand-600 text-white' : 'bg-gray-100 text-gray-400' }} {{ $now ? 'ring-4 ring-brand-100' : '' }}">
                            {{ $done && ! $now ? '✓' : $i + 1 }}
                        </span>
                        <p class="text-[11px] md:text-xs mt-2 {{ $done ? 'text-ink font-medium' : 'text-gray-400' }}">{{ ucfirst($f) }}</p>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>

    <div class="grid lg:grid-cols-3 gap-5 md:gap-6">

        {{-- ============ LEFT ============ --}}
        <div class="lg:col-span-2 space-y-5 md:space-y-6 min-w-0">

            {{-- items --}}
            <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.1s">
                <h2 class="font-semibold mb-4">Items <span class="text-gray-400 font-normal text-sm">({{ $items->count() }})</span></h2>

                {{-- mobile cards --}}
                <div class="md:hidden space-y-3">
                    @foreach($items as $item)
                        <div class="flex items-center justify-between gap-3 rounded-2xl border border-gray-100 p-3.5">
                            <div class="min-w-0">
                                <p class="text-sm font-medium leading-snug">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-400 mt-1">Rs. {{ number_format($item->price) }} × {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-semibold whitespace-nowrap">Rs. {{ number_format($item->price * $item->quantity) }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- desktop table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-[11px] uppercase tracking-wider text-gray-400">
                            <tr><th class="py-2 font-medium">Product</th><th class="font-medium">Price</th><th class="font-medium">Qty</th><th class="font-medium text-right">Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="border-t border-gray-100">
                                    <td class="py-3.5 pr-4 font-medium">{{ $item->product_name }}</td>
                                    <td class="whitespace-nowrap text-gray-500">Rs. {{ number_format($item->price) }}</td>
                                    <td><span class="inline-block min-w-[28px] text-center bg-brand-50 text-brand-600 text-xs font-semibold rounded-lg px-2 py-1">{{ $item->quantity }}</span></td>
                                    <td class="text-right font-semibold whitespace-nowrap">Rs. {{ number_format($item->price * $item->quantity) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 pt-5 border-t border-dashed border-gray-200 flex items-center justify-between">
                    <span class="text-sm text-gray-500">Order total</span>
                    <span class="text-2xl font-semibold text-brand-700">Rs. {{ number_format($order->total) }}</span>
                </div>
            </div>

            {{-- customer --}}
            <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.15s" x-data="{ copied: false }">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold">Shipping details</h2>
                    @if($fullAddress)
                        <button type="button" class="print:hidden text-xs text-brand-600 hover:underline"
                                @click="navigator.clipboard.writeText(@js($order->customer_name . ', ' . ($order->phone ?? '') . ', ' . $fullAddress)); copied = true; setTimeout(() => copied = false, 1800)"
                                x-text="copied ? 'Copied ✓' : 'Copy address'">Copy address</button>
                    @endif
                </div>

                <div class="flex items-center gap-4 mb-5">
                    <span class="w-12 h-12 rounded-2xl bg-gradient-to-br from-brand-400 to-bloom-400 text-white font-semibold text-lg flex items-center justify-center shrink-0">{{ strtoupper(substr($order->customer_name, 0, 1)) }}</span>
                    <div class="min-w-0">
                        <p class="font-medium truncate">{{ $order->customer_name }}</p>
                        @if($order->email)<a href="mailto:{{ $order->email }}" class="text-xs text-brand-600 hover:underline break-all">{{ $order->email }}</a>@endif
                    </div>
                </div>

                <dl class="grid sm:grid-cols-2 gap-3 text-sm">
                    <div class="bg-lav/60 rounded-2xl px-4 py-3">
                        <dt class="text-[11px] uppercase tracking-wider text-gray-400 mb-0.5">Phone</dt>
                        <dd class="font-medium">{{ $order->phone ?: '—' }}</dd>
                    </div>
                    <div class="bg-lav/60 rounded-2xl px-4 py-3">
                        <dt class="text-[11px] uppercase tracking-wider text-gray-400 mb-0.5">City</dt>
                        <dd class="font-medium">{{ $order->city ?: '—' }}</dd>
                    </div>
                    <div class="bg-lav/60 rounded-2xl px-4 py-3 sm:col-span-2">
                        <dt class="text-[11px] uppercase tracking-wider text-gray-400 mb-0.5">Address</dt>
                        <dd class="font-medium leading-relaxed">{{ $order->address ?: '—' }}</dd>
                    </div>
                </dl>

                @if($order->notes)
                    <div class="mt-4 rounded-2xl bg-amber-50 border border-amber-100 px-4 py-3 text-sm text-amber-800">
                        <p class="text-[11px] uppercase tracking-wider text-amber-600 mb-0.5">Customer notes</p>
                        {{ $order->notes }}
                    </div>
                @endif
            </div>
        </div>

        {{-- ============ RIGHT ============ --}}
        <div class="space-y-5 md:space-y-6">

            {{-- status update --}}
            <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.12s">
                <h2 class="font-semibold mb-4">Order status</h2>
                @can('orders.edit')
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="print:hidden">
                        @csrf
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            @foreach($meta as $s => [$_b, $d])
                                <label class="cursor-pointer {{ $s === 'cancelled' ? 'col-span-2' : '' }}">
                                    <input type="radio" name="status" value="{{ $s }}" class="peer sr-only" @checked($st === $s)>
                                    <span class="flex items-center gap-2 border border-gray-200 rounded-xl px-3 py-2.5 text-xs transition
                                                 peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 peer-checked:font-medium hover:border-brand-200">
                                        <span class="w-2 h-2 rounded-full {{ $d }}"></span>{{ ucfirst($s) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <button class="btn-bloom w-full text-white text-sm font-medium rounded-xl py-3 hover:opacity-90 active:scale-[.99] transition">Update status</button>
                    </form>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium {{ $badge }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>{{ ucfirst($order->status) }}
                    </span>
                @endcan
            </div>

            {{-- payment --}}
            <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.18s">
                <h2 class="font-semibold mb-4">Payment</h2>
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl {{ $isCod ? 'bg-amber-50' : 'bg-sky-50' }}">{{ $isCod ? '💵' : '🏦' }}</span>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider text-gray-400">Method</p>
                        <p class="text-sm font-medium">{{ $isCod ? 'Cash on Delivery' : 'Bank Transfer' }}</p>
                    </div>
                </div>
                @unless($isCod)
                    <p class="text-xs bg-sky-50 text-sky-700 rounded-xl px-3 py-2.5 mb-4 leading-relaxed">Customer was asked to share a transfer screenshot. Confirm the payment before shipping.</p>
                @endunless
                <div class="flex items-center justify-between text-sm border-t border-dashed border-gray-200 pt-4">
                    <span class="text-gray-500">Amount</span>
                    <strong class="text-base">Rs. {{ number_format($order->total) }}</strong>
                </div>
                <p class="text-[11px] text-gray-400 mt-3">Placed {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="print:hidden block text-center bg-white soft hover:bg-brand-50 text-gray-600 hover:text-brand-600 text-sm rounded-2xl py-3.5 transition">← Back to orders</a>
        </div>
    </div>
</div>
@endsection