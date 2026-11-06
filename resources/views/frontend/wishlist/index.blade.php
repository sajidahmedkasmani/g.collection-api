@extends('frontend.layout.master')
@section('title', 'My Wishlist')

@section('content')
    <section class="max-w-7xl mx-auto px-5 md:px-6 py-10 md:py-16">
        <div class="flex items-end justify-between gap-4 mb-8">
            <div>
                <p class="text-[11px] uppercase tracking-[0.2em] text-muted mb-2">Saved for later</p>
                <h1 class="font-serif text-4xl md:text-6xl">My wishlist</h1>
            </div>
            <a href="{{ route('shop') }}" class="text-sm underline underline-offset-4 hover:text-plum">Continue shopping</a>
        </div>

        <div id="wishlist-products">
            @if($products->isNotEmpty())
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-3 sm:gap-x-5 md:gap-x-6 gap-y-9 md:gap-y-12">
                    @foreach($products as $p)
                        @include('frontend.partials.product-card', ['p' => $p])
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-3xl">
                    <p class="font-serif text-3xl">Your wishlist is empty</p>
                    <p class="text-sm text-muted mt-2">Tap the heart on a product to save it here.</p>
                    <a href="{{ route('shop') }}" class="inline-block mt-6 bg-plum text-white rounded-full px-7 py-3 text-sm">Browse products</a>
                </div>
            @endif
        </div>
    </section>
@endsection