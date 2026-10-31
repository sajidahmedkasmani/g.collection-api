@extends('layouts.app')
@section('title', 'Order Confirmed')

@section('content')
@php $s = config('app.settings', []); @endphp
<div class="max-w-2xl mx-auto px-4 py-16">
    <div class="bg-white rounded-xl shadow-sm p-10 text-center">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">✓</div>
        <h1 class="text-3xl font-bold mb-2">Order Confirmed!</h1>
        <p class="text-gray-600 mb-6">Order #: <strong>{{ $order->order_number }}</strong></p>
        @if($order->payment_method === 'bank_transfer')
            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-5 text-left mb-6">
                <h3 class="font-bold mb-2">Bank Transfer Instructions</h3>
                <p class="text-sm mb-2">Please transfer <strong>Rs. {{ number_format($order->total) }}</strong> to:</p>
                <ul class="text-sm space-y-1">
                    <li><strong>Bank:</strong> {{ $s['bank_name'] ?? 'Meezan Bank' }}</li>
                    <li><strong>Account Title:</strong> {{ $s['bank_account_title'] ?? 'G. Collection' }}</li>
                    <li><strong>Account No:</strong> {{ $s['bank_account_no'] ?? '1234567890' }}</li>
                    <li><strong>IBAN:</strong> {{ $s['bank_iban'] ?? 'PK00MEZN0000000012345678' }}</li>
                </ul>
                <p class="text-sm mt-3 text-gray-600">After transfer, send screenshot to WhatsApp: {{ $s['whatsapp_number'] ?? '0300-1234567' }}</p>
            </div>
        @else
            <div class="bg-green-50 border border-green-300 rounded-lg p-5 mb-6">
                <p class="text-sm">You will pay <strong>Rs. {{ number_format($order->total) }}</strong> in cash when your order is delivered.</p>
            </div>
        @endif

        <div class="text-left border-t pt-4">
            <h3 class="font-semibold mb-2">Items</h3>
            @foreach($order->items as $item)
                <div class="flex justify-between py-1 text-sm">
                    <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                    <span>Rs. {{ number_format($item->price * $item->quantity) }}</span>
                </div>
            @endforeach
        </div>

        <a href="{{ route('shop') }}" class="inline-block mt-8 bg-rose-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-rose-700">Continue Shopping</a>
    </div>
</div>
@endsection