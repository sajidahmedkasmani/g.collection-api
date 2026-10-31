@extends('layouts.app')
@section('title', 'My Account')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Welcome, {{ auth('customer')->user()->name }}</h1>
        <form method="POST" action="{{ route('customer.logout') }}">
            @csrf
            <button class="text-sm text-gray-500 hover:underline">Logout</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-medium mb-3">My Orders</h2>
        
        @php
            $orders = auth('customer')->user()->orders()->latest()->take(5)->get();
        @endphp

        @forelse($orders as $order)
            <div class="flex justify-between py-2 border-b text-sm">
                <span>#{{ $order->order_number }}</span>
                <span>Rs. {{ number_format($order->total) }}</span>
                <span class="text-xs px-2 py-1 rounded bg-gray-100">{{ $order->status }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-500">No orders yet.</p>
        @endforelse
    </div>
</div>
@endsection