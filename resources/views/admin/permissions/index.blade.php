@extends('admin.layout')
@section('title', 'Manage Permissions')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-ink">Manage Permissions</h1>
        <p class="text-sm text-gray-500 mt-1">Create permission keys and turn existing permissions on or off.</p>
    </div>

    <div class="bg-white rounded-2xl p-6 soft">
        <h2 class="text-base font-bold text-ink mb-1">Add a permission</h2>
        <p class="text-xs text-gray-500 mb-5">
            The permission key must match an ability used by an admin route, such as <code>reports.view</code>.
        </p>

        <form method="POST" action="{{ route('admin.permissions.store') }}" class="grid md:grid-cols-4 gap-4 items-start">
            @csrf
            <div>
                <label for="permission-name" class="block text-xs font-semibold text-gray-700 mb-1.5">Permission key</label>
                <input id="permission-name" name="name" value="{{ old('name') }}" required maxlength="100"
                       placeholder="reports.view" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                @error('name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="permission-label" class="block text-xs font-semibold text-gray-700 mb-1.5">Display name</label>
                <input id="permission-label" name="label" value="{{ old('label') }}" required maxlength="120"
                       placeholder="View Reports" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                @error('label') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="permission-module" class="block text-xs font-semibold text-gray-700 mb-1.5">Module</label>
                <input id="permission-module" name="module" value="{{ old('module') }}" required maxlength="100"
                       placeholder="Reports" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                @error('module') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="pt-5">
                <button class="btn-bloom text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:opacity-90 transition">
                    Add Permission
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl soft overflow-hidden">
        @forelse($permissions as $module => $modulePermissions)
            <section class="p-5 {{ !$loop->first ? 'border-t border-gray-100' : '' }}">
                <h2 class="font-semibold text-sm text-ink mb-3">{{ $module }}</h2>
                <div class="space-y-2">
                    @foreach($modulePermissions as $permission)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-gray-100 rounded-xl px-4 py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $permission->label }}</p>
                                <code class="text-xs text-gray-400">{{ $permission->name }}</code>
                            </div>
                            <form method="POST" action="{{ route('admin.permissions.toggle-active', $permission) }}">
                                @csrf
                                <button class="px-3 py-1.5 rounded-full text-[11px] font-semibold transition {{ $permission->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    {{ $permission->is_active ? 'Active — click to deactivate' : 'Inactive — click to activate' }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <p class="p-8 text-center text-sm text-gray-500">No permissions have been created yet.</p>
        @endforelse
    </div>
</div>
@endsection
