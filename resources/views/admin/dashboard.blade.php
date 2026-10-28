@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    @foreach(['products' => 'Products', 'orders' => 'Orders', 'pending_orders' => 'Pending', 'revenue' => 'Revenue (Rs.)'] as $k => $label)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">{{ $label }}</p>
            <p class="text-3xl font-bold mt-1">{{ $k === 'revenue' ? number_format($stats[$k]) : $stats[$k] }}</p>
        </div>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="font-bold mb-4">Recent Orders</h2>
    <table class="w-full text-sm">
        <thead class="text-left text-gray-500 border-b">
            <tr>
                <th class="py-2">Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $o)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">
                        <a href="{{ route('admin.orders.show', $o) }}" class="text-blue-600 font-mono text-xs">{{ $o->order_number }}</a>
                    </td>
                    <td>{{ $o->customer_name }}</td>
                    <td>Rs. {{ number_format($o->total) }}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">{{ ucfirst($o->status) }}</span>
                    </td>
                    <td class="text-xs text-gray-500">{{ $o->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-500">No orders yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection