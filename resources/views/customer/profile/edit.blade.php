@extends('frontend.layout.master')
@section('title', 'Edit Profile')

@section('content')
@php
    $input = 'w-full rounded-xl border border-black/15 bg-white px-4 py-3 text-sm focus:outline-none focus:border-plum focus:ring-4 focus:ring-rose/15 transition';
@endphp

<div class="max-w-2xl mx-auto px-4 sm:px-6 py-10 md:py-16">
    <a href="{{ route('customer.dashboard') }}" class="text-sm text-muted hover:text-plum">← Back to my account</a>
    <div class="bg-white rounded-2xl border border-black/5 p-6 sm:p-8 mt-5">
        <h1 class="font-serif text-3xl mb-2">Edit profile</h1>
        <p class="text-sm text-muted mb-6">Update your contact details or set a new password.</p>

        @if(session('success'))
            <div role="status" class="rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 mb-5">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div role="alert" class="rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 mb-5">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="profile-name" class="block text-sm font-medium mb-1.5">Full name</label>
                <input id="profile-name" type="text" name="name" value="{{ old('name', $customer->name) }}" required maxlength="255" autocomplete="name" class="{{ $input }}">
                @error('name') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="profile-email" class="block text-sm font-medium mb-1.5">Email</label>
                <input id="profile-email" type="email" name="email" value="{{ old('email', $customer->email) }}" required autocomplete="email" class="{{ $input }}">
                @error('email') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label for="profile-phone" class="block text-sm font-medium mb-1.5">Phone</label>
                    <input id="profile-phone" type="tel" name="phone" value="{{ old('phone', $customer->phone) }}" required maxlength="20" autocomplete="tel" class="{{ $input }}">
                    @error('phone') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="profile-city" class="block text-sm font-medium mb-1.5">City</label>
                    <input id="profile-city" type="text" name="city" value="{{ old('city', $customer->city) }}" maxlength="100" autocomplete="address-level2" class="{{ $input }}">
                    @error('city') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="border-t border-black/10 pt-5">
                <h2 class="font-medium">Change password <span class="text-muted font-normal">(optional)</span></h2>
                <p class="text-xs text-muted mt-1 mb-4">Leave both password fields blank to keep your current password.</p>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="profile-password" class="block text-sm font-medium mb-1.5">New password</label>
                        <input id="profile-password" type="password" name="password" minlength="8" autocomplete="new-password" class="{{ $input }}">
                        @error('password') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="profile-password-confirmation" class="block text-sm font-medium mb-1.5">Confirm new password</label>
                        <input id="profile-password-confirmation" type="password" name="password_confirmation" minlength="8" autocomplete="new-password" class="{{ $input }}">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full sm:w-auto bg-plum text-cream rounded-full px-7 py-3 text-sm font-medium hover:bg-ink transition">
                Save changes
            </button>
        </form>
    </div>
</div>
@endsection
