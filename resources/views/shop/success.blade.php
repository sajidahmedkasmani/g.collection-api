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

        {{-- Post-purchase account creation prompt (sirf guest ke liye) --}}
        @guest('customer')
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-6 mt-8 text-left">
                <h3 class="text-lg font-semibold text-rose-800 mb-2">Create an account & earn loyalty rewards</h3>
                <p class="text-sm text-rose-700 mb-4">
                    Your order is confirmed! Create an account with the same email
                    (<strong>{{ $order->customer_email ?? $order->email }}</strong>) to track this order
                    and earn points on every future purchase.
                </p>
                <a href="{{ route('customer.register', ['email' => $order->customer_email ?? $order->email]) }}"
                   class="inline-block bg-rose-600 text-white text-sm rounded-lg px-5 py-2 hover:bg-rose-700">
                    Create Account
                </a>
                <a href="{{ route('shop') }}"
                   class="inline-block ml-2 border border-rose-300 text-rose-700 text-sm rounded-lg px-5 py-2 hover:bg-rose-100">
                    Continue as Guest
                </a>
            </div>
        @endguest

        @auth('customer')
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mt-8 text-sm text-green-800">
                Points earned on this order will appear in your account dashboard.
                <a href="{{ route('customer.dashboard') }}" class="underline font-medium">View account</a>
            </div>
        @endauth

        <a href="{{ route('shop') }}"
           class="inline-block mt-8 bg-rose-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-rose-700">
            Continue Shopping
        </a>
    </div>
</div>
@endsection