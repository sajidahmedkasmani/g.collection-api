@extends('frontend.layout.master')
@section('title', 'Checkout')

@section('content')
    @include('frontend.shop._checkout_content', ['cart' => $cart, 'summary' => $summary])
@endsection
