@extends('layouts.app')
@section('title', 'Shop — G. Collection')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8">All Products</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($products as $p)
            <a href="{{ route('product.show', $p->slug) }}" class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden">
                <div class="aspect-square bg-gray-100 overflow-hidden">
                    <img src="{{ $p->image ?? 'https://placehold.co/400x400?text=Product' }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-sm truncate">{{ $p->name }}</h3>
                    <p class="text-rose-600 font-bold mt-1">Rs. {{ number_format($p->price) }}</p>
                </div>
            </a>
        @empty
            <p class="col-span-4 text-gray-500">No products yet.</p>
        @endforelse
    </div>
    <div class="mt-10">{{ $products->links() }}</div>
</div>
@endsection