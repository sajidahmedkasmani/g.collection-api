@extends('admin.layout')
@section('title', 'Edit User — ' . $user->name)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-ink">Edit User — {{ $user->name }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Update credentials, status, and module permissions.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="bg-white soft text-gray-700 hover:text-brand-600 px-4 py-2 rounded-xl text-xs font-medium transition">
            ← Back to Users
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('admin.users._form', [
            'user'            => $user,
            'permissions'     => $permissions,
            'userPermissions' => $userPermissions,
        ])

        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                Cancel
            </a>
            <button type="submit" class="btn-bloom text-white px-7 py-2.5 rounded-xl text-xs font-semibold hover:opacity-90 transition shadow-sm">
                Save User & Permissions
            </button>
        </div>
    </form>
</div>
@endsection