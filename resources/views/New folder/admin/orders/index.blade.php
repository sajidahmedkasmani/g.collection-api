@extends('admin.layout')
@section('title', 'Orders')

@section('content')
<div class="flex gap-2 mb-4 flex-wrap">
    @php
        $statuses = ['', 'pending', 'confirmed', 'shipped', 'completed', 'cancelled'];
    @endphp
    @foreach($statuses as $s)
        <a href="{{ route('admin.orders.index', $s ? ['status' => $s] : []) }}"
           class="px-3 py-1.5 rounded-lg text-sm {{ request('status') === $s || (!request('status') && !$s) ? 'bg-blue-600 text-white' : 'bg-white border' }}">
            {{ $s ? ucfirst($s) : 'All' }}
        </a>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="py-3 px-4">Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $o)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-mono text-xs">{{ $o->order_number }}</td>
                    <td>
                        <div class="font-medium">{{ $o->customer_name }}</div>
                        <div class="text-xs text-gray-500">{{ $o->phone }}</div>
                    </td>
                    <td>Rs. {{ number_format($o->total) }}</td>
                    <td class="text-xs uppercase">{{ $o->payment_method === 'cod' ? 'COD' : 'Bank' }}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">
                            {{ ucfirst($o->status) }}
                        </span>
                    </td>
                    <td class="text-xs text-gray-500">{{ $o->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $o) }}" class="text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-8 text-center text-gray-500">No orders yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection