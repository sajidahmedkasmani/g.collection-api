@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12 p-6 bg-white rounded-xl shadow">
    <h1 class="text-2xl font-semibold mb-6">Customer Login</h1>

    @if($errors->any())
        <div class="bg-red-50 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('customer.login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm" required autofocus>
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember"> Remember me
        </label>
        <button type="submit" class="w-full bg-black text-white text-sm rounded-lg px-4 py-2 hover:opacity-80">
            Login
        </button>
    </form>

    <p class="text-sm text-gray-500 mt-4 text-center">
        Don't have an account?
        <a href="{{ route('customer.register') }}" class="text-blue-600 hover:underline">Create one</a>
    </p>
</div>
@endsection