@extends('admin.layout')
@section('title', 'Import Products')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    <h2 class="text-lg font-bold mb-2">Import Products from CSV</h2>
    <p class="text-sm text-gray-500 mb-6">CSV file upload karo. System sirf neeche wale columns accept karega.</p>

    <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="text-sm font-medium">CSV File</label>
            <input type="file" name="csv_file" accept=".csv,.txt" required
                   class="w-full border rounded-lg px-3 py-2 mt-1">
            @error('csv_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-sm">
            <p class="font-medium mb-2">Expected Columns:</p>
            <table class="w-full text-xs">
                <thead class="text-left border-b">
                    <tr><th class="py-1">Column</th><th>Zaroori?</th><th>Example</th></tr>
                </thead>
                <tbody>
                    <tr><td><code>name</code></td><td>Haan</td><td>Mint Green Floral Suit</td></tr>
                    <tr><td><code>price</code></td><td>Haan</td><td>2500</td></tr>
                    <tr><td><code>description</code></td><td>Nahi</td><td>2-piece stitched suit</td></tr>
                    <tr><td><code>image</code></td><td>Nahi</td><td>https://example.com/img.jpg</td></tr>
                    <tr><td><code>stock</code></td><td>Nahi</td><td>10</td></tr>
                    <tr><td><code>category</code></td><td>Nahi</td><td>stitched / unstitched / accessories</td></tr>
                    <tr><td><code>is_active</code></td><td>Nahi</td><td>1 ya 0</td></tr>
                </tbody>
            </table>
        </div>

        <div class="flex gap-3">
            <button class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm">Import</button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection