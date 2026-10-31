@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="max-w-md mx-auto mt-16 p-6 bg-white rounded-xl shadow">
    <h1 class="text-2xl font-semibold mb-6">Create Account</h1>

    @if($errors->any())
        <div class="bg-red-50 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('customer.register') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm" required>
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', request('email')) }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm" required>
            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   placeholder="03xx-xxxxxxx"
                   class="w-full border rounded-lg px-3 py-2 text-sm" required>
            @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">
                City <span class="text-gray-400 normal-case">(optional)</span>
            </label>
            <input type="text" name="city" value="{{ old('city') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
            @error('city') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 text-sm" required>
            @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        </div>

        <button type="submit" class="w-full bg-black text-white text-sm rounded-lg px-4 py-2 hover:opacity-80">
            Register
        </button>
    </form>

    <p class="text-sm text-gray-500 mt-4 text-center">
        Already have an account?
        <a href="{{ route('customer.login') }}" class="text-blue-600 hover:underline">Login</a>
    </p>
</div>
@endsection