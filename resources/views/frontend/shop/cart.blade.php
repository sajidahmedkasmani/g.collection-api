@extends('frontend.layout.master')

@section('title', 'Your Cart')

@section('content')
    @include('frontend.shop._cart_content', ['cart' => $cart, 'summary' => $summary])
@endsection
