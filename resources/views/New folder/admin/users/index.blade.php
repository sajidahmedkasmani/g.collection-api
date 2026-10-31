@extends('admin.layout')
@section('title', 'Users')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Users</h1>
        @can('users.create')
            <a href="{{ route('admin.users.create') }}"
               class="bg-blue-600 text-white text-sm rounded-lg px-4 py-2 hover:bg-blue-700">
                + New User
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-3 px-4">Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Permissions</th>
                    <th class="text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                                <span class="text-xs text-gray-400">(you)</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs
                                {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-xs text-gray-500">
                            {{ $user->permissions_count }} permissions
                        </td>
                        <td class="text-right pr-4 space-x-3">
                            @can('users.edit')
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="text-blue-600 hover:underline">Edit</a>
                            @endcan

                            @can('users.toggle-active')
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-xs {{ $user->is_active ? 'text-yellow-600' : 'text-green-600' }} hover:underline">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                @endif
                            @endcan

                            @can('users.delete')
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                          onsubmit="return confirm('Delete this user?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-gray-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection