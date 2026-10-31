@extends('admin.layout')
@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold text-lg mb-4">Items</h2>
            <table class="w-full text-sm">
                <thead class="text-left border-b">
                    <tr><th class="py-2">Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b">
                            <td class="py-2">{{ $item->product_name }}</td>
                            <td>Rs. {{ number_format($item->price) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs. {{ number_format($item->price * $item->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold">
                        <td colspan="3" class="pt-3 text-right">Total:</td>
                        <td class="pt-3">Rs. {{ number_format($order->total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold text-lg mb-3">Shipping Details</h2>
            <dl class="grid grid-cols-2 gap-2 text-sm">
                <dt class="text-gray-500">Name</dt><dd>{{ $order->customer_name }}</dd>
                <dt class="text-gray-500">Email</dt><dd>{{ $order->email }}</dd>
                <dt class="text-gray-500">Phone</dt><dd>{{ $order->phone }}</dd>
                <dt class="text-gray-500">City</dt><dd>{{ $order->city }}</dd>
                <dt class="text-gray-500">Address</dt><dd class="col-span-1">{{ $order->address }}</dd>
            </dl>
            @if($order->notes)
                <div class="mt-3 p-3 bg-gray-50 rounded text-sm">
                    <strong>Notes:</strong> {{ $order->notes }}
                </div>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold mb-3">Order Status</h2>
            <p class="mb-3">
                <span class="px-3 py-1 rounded text-sm bg-yellow-100 text-yellow-700">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf
                <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm mb-2">
                    @foreach(['pending','confirmed','shipped','completed','cancelled'] as $s)
                        <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm">Update Status</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold mb-3">Payment</h2>
            <p class="text-sm">
                Method: <strong class="uppercase">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}</strong>
            </p>
            <p class="text-sm mt-1">Total: <strong>Rs. {{ number_format($order->total) }}</strong></p>
            <p class="text-xs text-gray-500 mt-2">Placed: {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="block text-center bg-gray-200 py-2 rounded-lg text-sm">← Back to Orders</a>
    </div>
</div>
@endsection