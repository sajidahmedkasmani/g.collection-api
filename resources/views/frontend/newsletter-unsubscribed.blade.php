@extends('frontend.layout.master')
@section('title', 'Newsletter Preferences')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-20 text-center">
    <h1 class="font-serif text-4xl">You are unsubscribed</h1>
    <p class="text-muted mt-3">You will no longer receive marketing newsletters from G. Collection.</p>
    <a href="{{ route('home') }}" class="inline-block mt-7 bg-plum text-cream px-8 py-3 rounded-full">Back to home</a>
</div>
@endsection
