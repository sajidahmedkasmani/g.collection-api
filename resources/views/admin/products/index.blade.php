@extends('admin.layout')
@section('title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-4 flex-wrap gap-3">
    <form method="GET" class="flex gap-2 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
               class="border rounded-lg px-3 py-2 text-sm w-64">
        <select name="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </select>
        <button class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:underline">Clear</a>
        @endif
    </form>
    <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">+ Add Product</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="py-3 px-4">Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">
                        @if($p->image)
                            <img src="{{ str_starts_with($p->image, 'http') ? $p->image : asset('storage/' . $p->image) }}"
                                 class="w-12 h-12 object-cover rounded">
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="font-medium">{{ $p->name }}</td>
                    <td>Rs. {{ number_format($p->price) }}</td>
                    <td>{{ $p->stock ?? '∞' }}</td>
                    <td>
                        @if($p->is_active)
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-600">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $p) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                        <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-gray-500">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection