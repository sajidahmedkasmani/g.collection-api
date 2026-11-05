@extends('admin.admin_layout.master')
@section('title', 'Collections & Categories')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @if(session('success'))
        <div role="status" class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div role="alert" class="rounded-xl bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">{{ $errors->first() }}</div>
    @endif

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-ink">Collections &amp; Categories</h1>
        <p class="text-sm text-gray-500 mt-1">Organize products into collections, then place categories inside each collection.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-5">
        <form method="POST" action="" class="bg-white rounded-2xl p-5 soft space-y-3">
            @csrf
            <h2 class="font-semibold">Add collection</h2>
            <label for="collection-name" class="sr-only">Collection name</label>
            <input id="collection-name" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="e.g. Clothing or Bags" class="w-full border rounded-xl px-3 py-2.5 text-sm">
            <button class="btn-bloom text-white px-5 py-2.5 rounded-xl text-xs font-semibold">Create collection</button>
        </form>

        <form method="POST" action="" class="bg-white rounded-2xl p-5 soft space-y-3">
            @csrf
            <h2 class="font-semibold">Add category</h2>
            <label for="category-collection" class="sr-only">Parent collection</label>
            <select id="category-collection" name="collection_id" required class="w-full border rounded-xl px-3 py-2.5 text-sm">
                <option value="">Choose a collection</option>
                @foreach($collections->where('is_active', true) as $collection)
                    <option value="{{ $collection->id }}" @selected(old('collection_id') == $collection->id)>{{ $collection->name }}</option>
                @endforeach
            </select>
            <label for="category-name" class="sr-only">Category name</label>
            <input id="category-name" name="name" value="{{ old('name') }}" required maxlength="40" placeholder="e.g. Handbags or Stitched" class="w-full border rounded-xl px-3 py-2.5 text-sm">
            <button class="btn-bloom text-white px-5 py-2.5 rounded-xl text-xs font-semibold">Create category</button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($collections as $collection)
            <section class="bg-white rounded-2xl soft overflow-hidden">
                <div class="px-5 py-4 flex items-center justify-between gap-4 border-b border-gray-100">
                    <div>
                        <h2 class="font-semibold">{{ $collection->name }}</h2>
                        <p class="text-xs text-gray-500">{{ $collection->categories->count() }} categories · {{ $collection->slug }}</p>
                    </div>
                    <form method="POST" action="">
                        @csrf
                        <button class="px-3 py-1.5 rounded-full text-xs {{ $collection->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $collection->is_active ? 'Active · Deactivate' : 'Inactive · Activate' }}
                        </button>
                    </form>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($collection->categories as $category)
                        <div class="px-5 py-3 flex items-center justify-between gap-4">
                            <div>
                                <span class="text-sm font-medium">{{ $category->name }}</span>
                                <span class="text-xs text-gray-400 ml-2">{{ $category->slug }}</span>
                            </div>
                            <form method="POST" action="">
                                @csrf
                                <button class="px-3 py-1.5 rounded-full text-xs {{ $category->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $category->is_active ? 'Active · Deactivate' : 'Inactive · Activate' }}
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="px-5 py-4 text-sm text-gray-500">No categories yet.</p>
                    @endforelse
                </div>
            </section>
        @empty
            <p class="bg-white rounded-2xl p-8 text-center text-sm text-gray-500">Create your first collection to get started.</p>
        @endforelse
    </div>
</div>
@endsection
