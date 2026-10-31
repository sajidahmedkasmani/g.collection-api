@extends('layouts.app')

@section('title', '404')

@section('content')

    <!-- Main Content -->
    <div class="text-center px-6 py-4">
        
        <!-- 404 Number -->
        <h1 class="text-[110px] md:text-[150px] leading-none font-light tracking-tighter mb-2">
            404
        </h1>
        
        <!-- Message -->
        <p class="text-[15px] md:text-base text-gray-800 mb-8">
            This page isn't in the collection.
        </p>
        
        <!-- Button -->
        <a href="{{ url('/') }}" 
           class="inline-block bg-ink text-white text-sm font-medium rounded-full px-8 py-3.5 hover:opacity-80 transition-opacity duration-300">
            Back to shop
        </a>
        
    </div>

@endsection
