@extends('admin.layout')
@section('title', 'Create User')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold mb-6">Create User</h1>

    <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-xl shadow-sm p-6">
        @csrf
        @include('admin.users._form', ['permissions' => $permissions])

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 text-white text-sm rounded-lg px-5 py-2 hover:bg-blue-700">
                Create User
            </button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:underline self-center">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection