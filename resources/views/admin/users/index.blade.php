@extends('admin.admin_layout.master')
@section('title', 'Admin Users')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-ink">Admin Users & Permissions</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage team members and configure granular module access.</p>
        </div>
        @can('users.create')
            <a href="{{ route('admin.users.create') }}"
               class="btn-bloom text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:opacity-90 transition flex items-center gap-1.5 shadow-sm">
                <span class="text-base leading-none">+</span> Add New User
            </a>
        @endcan
        @can('users.edit')
            <a href="{{ route('admin.permissions.index') }}"
               class="bg-white soft text-brand-700 px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-brand-50 transition">
                Manage Permissions
            </a>
        @endcan
    </div>

    <div class="bg-white rounded-2xl soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="py-3.5 px-5">User</th>
                        <th class="py-3.5 px-5">Email</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                        <th class="py-3.5 px-5">Access Rights</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-bloom-400 text-white text-xs font-semibold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="font-medium text-ink flex items-center gap-1.5">
                                            {{ $u->name }}
                                            @if($u->id === auth()->id())
                                                <span class="text-[10px] bg-brand-50 text-brand-600 px-1.5 py-0.5 rounded-md font-medium">(You)</span>
                                            @endif
                                            @if($u->id === 1)
                                                <span class="text-[10px] bg-amber-50 text-amber-700 px-1.5 py-0.5 rounded-md font-medium">Super Admin</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5 text-gray-600 font-mono text-xs">{{ $u->email }}</td>
                            <td class="py-4 px-5 text-center">
                                @can('users.toggle-active')
                                    @if($u->id !== auth()->id() && $u->id !== 1)
                                        <form method="POST" action="{{ route('admin.users.toggle-active', $u) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-semibold transition {{ $u->is_active ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                                {{ $u->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-600">Active</span>
                                    @endif
                                @else
                                    <span class="px-3 py-1 rounded-full text-[11px] font-semibold {{ $u->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $u->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                @endcan
                            </td>
                            <td class="py-4 px-5">
                                @if($u->id === 1)
                                    <span class="inline-flex items-center gap-1 text-xs text-amber-600 font-medium bg-amber-50 px-2.5 py-1 rounded-lg">
                                        All Permissions (Root)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium bg-brand-50 text-brand-700 px-2.5 py-1 rounded-lg">
                                        {{ $u->permissions_count }} permissions assigned
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-right space-x-2">
                                @can('users.edit')
                                    <a href="{{ route('admin.users.edit', $u) }}"
                                       class="inline-flex items-center text-xs font-semibold text-brand-600 hover:text-brand-700 bg-brand-50 px-3 py-1.5 rounded-lg transition">
                                        Edit / Permissions
                                    </a>
                                @endcan

                                @can('users.delete')
                                    @if($u->id !== auth()->id() && $u->id !== 1)
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 px-3 py-1.5 rounded-lg transition">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection