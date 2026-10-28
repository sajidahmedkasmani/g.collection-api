@extends('admin.layout')
@section('title', $product->exists ? 'Edit Product' : 'Add Product')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if($product->exists) @method('PUT') @endif

        <div class="mb-4">
            <label class="text-sm font-medium">Product Name *</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                   class="w-full border rounded-lg px-3 py-2 mt-1">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="text-sm font-medium">Price (Rs.) *</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                       class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>
            <div>
                <label class="text-sm font-medium">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                       class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>
        </div>

        <div class="mb-4">
    <label class="text-sm font-medium">Category</label>
    <select name="category" class="w-full border rounded-lg px-3 py-2 mt-1">
        <option value="">— Select —</option>
        @foreach(['stitched', 'unstitched', 'accessories', 'other'] as $cat)
            <option value="{{ $cat }}" @selected(old('category', $product->category) === $cat)>{{ ucfirst($cat) }}</option>
        @endforeach
    </select>
</div>

        <div class="mb-4">
            <label class="text-sm font-medium">Description</label>
            <textarea name="description" rows="4" class="w-full border rounded-lg px-3 py-2 mt-1">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="text-sm font-medium">Image Upload</label>
            <input type="file" name="image_file" accept="image/*" class="w-full border rounded-lg px-3 py-2 mt-1">
            <p class="text-xs text-gray-500 mt-1">Or provide URL below</p>
        </div>

        <div class="mb-4">
            <label class="text-sm font-medium">Image URL</label>
            <input type="text" name="image" value="{{ old('image', $product->image) }}"
                   placeholder="https://example.com/image.jpg" class="w-full border rounded-lg px-3 py-2 mt-1">
        </div>

        @if($product->image)
            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-2">Current Image:</p>
                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                     class="w-32 h-32 object-cover rounded">
            </div>
        @endif

        <label class="flex items-center gap-2 mb-4">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
            <span class="text-sm font-medium">Active (visible on store)</span>
        </label>

        <div class="flex gap-3">
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm">Save</button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection