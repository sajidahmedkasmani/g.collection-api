@extends('admin.layout')
@section('title', 'Products')

@section('content')
<div x-data="{
    selected: [],
    allIds: [{{ $products->pluck('id')->join(',') }}],
    get allChecked() { return this.allIds.length > 0 && this.selected.length === this.allIds.length; },
    toggleAll() {
        this.selected = this.allChecked ? [] : [...this.allIds];
    }
}" class="space-y-4">

    {{-- Top bar: search + add --}}
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
        <div class="flex gap-2">
            <a href="{{ route('admin.products.import.form') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Import CSV</a>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">+ Add Product</a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 text-sm rounded-lg px-4 py-3">{{ session('error') }}</div>
    @endif

    {{-- Bulk actions bar (visible only when selection > 0) --}}
    <div x-show="selected.length > 0" x-cloak
         class="flex items-center gap-3 bg-gray-50 border rounded-xl px-4 py-3">
        <span class="text-sm text-gray-600">
            <span x-text="selected.length"></span> selected
        </span>

        {{-- Bulk status update --}}
        <form method="POST" action="{{ route('admin.products.bulk-status') }}" class="inline">
            @csrf
            <template x-for="id in selected" :key="'s'+id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <input type="hidden" name="status" value="1">
            <button type="submit" class="bg-green-600 text-white text-xs rounded-lg px-4 py-2 hover:bg-green-700">
                Activate
            </button>
        </form>

        <form method="POST" action="{{ route('admin.products.bulk-status') }}" class="inline">
            @csrf
            <template x-for="id in selected" :key="'s0'+id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <input type="hidden" name="status" value="0">
            <button type="submit" class="bg-gray-600 text-white text-xs rounded-lg px-4 py-2 hover:bg-gray-700">
                Deactivate
            </button>
        </form>

        {{-- Bulk delete --}}
        <form method="POST" action="{{ route('admin.products.bulk-delete') }}" class="inline"
              onsubmit="return confirm('Delete selected products? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <template x-for="id in selected" :key="'d'+id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="submit" class="bg-red-600 text-white text-xs rounded-lg px-4 py-2 hover:bg-red-700">
                Delete
            </button>
        </form>

        <button type="button" @click="selected = []" class="text-xs text-gray-500 hover:underline ml-auto">
            Clear selection
        </button>
    </div>

    {{-- Products table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-3 px-4 w-10">
                        <input type="checkbox"
                               :checked="allChecked"
                               @change="toggleAll()"
                               class="rounded border-gray-300">
                    </th>
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
                            <input type="checkbox"
                                   value="{{ $p->id }}"
                                   x-model.number="selected"
                                   class="rounded border-gray-300">
                        </td>
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
                    <tr><td colspan="7" class="py-8 text-center text-gray-500">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection