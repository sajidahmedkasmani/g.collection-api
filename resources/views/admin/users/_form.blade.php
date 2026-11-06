@php
    $selected = old('permissions', $userPermissions ?? []);
    $isEdit = isset($user) && $user->exists;
@endphp

<div class="space-y-6">

    {{-- SECTION 1: USER DETAILS --}}
    <div class="bg-white rounded-2xl p-6 soft border border-gray-100">
        <div class="border-b border-gray-100 pb-3 mb-5">
            <h2 class="text-base font-bold text-ink flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                User Account Details
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Basic profile credentials for admin panel login.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-5 mb-5">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required placeholder="e.g. Ahmed Khan" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required placeholder="name@domain.com" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Role</label>
                <input type="text" name="role" value="{{ old('role', $user->role ?? '') }}" maxlength="100" placeholder="e.g. Manager, Staff" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                @error('role') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    Password {{ $isEdit ? '(Optional)' : '*' }}
                </label>
                <input type="password" name="password" {{ $isEdit ? '' : 'required' }} placeholder="{{ $isEdit ? 'Leave blank to keep existing password' : 'Min 8 characters' }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                @if($isEdit)
                    <span class="text-[11px] text-gray-400 mt-1 block">Only enter a password if you want to change it.</span>
                @endif
                @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" {{ $isEdit ? '' : 'required' }} placeholder="Re-type password" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100">
            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500">
                <span class="text-sm font-medium text-gray-700">Account is Active (User can log in to admin panel)</span>
            </label>
        </div>
    </div>

    {{-- SECTION 2: MODULE PERMISSIONS --}}
    <div class="bg-white rounded-2xl p-6 soft border border-gray-100" x-data="{
        selectAll() {
            document.querySelectorAll('input[type=checkbox][name=\'permissions[]\']').forEach(cb => cb.checked = true);
        },
        deselectAll() {
            document.querySelectorAll('input[type=checkbox][name=\'permissions[]\']').forEach(cb => cb.checked = false);
        }
    }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4 mb-5">
            <div>
                <h2 class="text-base font-bold text-ink flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-bloom-500"></span>
                    Module & Section Permissions
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">Control exactly which modules and actions this user has access to.</p>
            </div>

            <div class="flex items-center gap-2 text-xs">
                <button type="button" @click="selectAll()" class="px-3 py-1.5 rounded-lg bg-brand-50 text-brand-700 font-medium hover:bg-brand-100 transition">
                    ✓ Select All
                </button>
                <button type="button" @click="deselectAll()" class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 font-medium hover:bg-gray-200 transition">
                    ✕ Deselect All
                </button>
            </div>
        </div>

        @if($isEdit && $user->id === 1)
            <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-3">
                <span class="text-xl">👑</span>
                <span><strong>Super Administrator:</strong> User ID 1 has permanent unrestricted access to all modules regardless of checkbox selections.</span>
            </div>
        @endif

        <div class="space-y-4">
            @foreach($permissions as $module => $modulePermissions)
                <div class="border border-gray-200/80 rounded-2xl p-4 bg-gray-50/40 hover:bg-white transition"
                     x-data="{
                        toggleModule() {
                            const boxes = this.$el.querySelectorAll('input[type=checkbox][name=\'permissions[]\']');
                            const allChecked = [...boxes].every(b => b.checked);
                            boxes.forEach(b => b.checked = !allChecked);
                        }
                     }">
                    <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-ink">{{ $module }}</span>
                            <span class="text-[10px] uppercase font-semibold px-2 py-0.5 rounded-full bg-brand-100 text-brand-700">
                                {{ $modulePermissions->count() }} actions
                            </span>
                        </div>
                        <button type="button" @click="toggleModule()" class="text-xs font-semibold text-brand-600 hover:text-brand-700 hover:underline">
                            Toggle all
                        </button>
                    </div>

                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($modulePermissions as $permission)
                            <label class="flex items-start gap-2.5 p-2 rounded-xl bg-white border border-gray-100 hover:border-brand-300 cursor-pointer transition">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->id }}"
                                       {{ in_array($permission->id, $selected) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 mt-0.5">
                                <div class="min-w-0">
                                    <span class="block text-xs font-medium text-gray-800 leading-snug">{{ $permission->label }}</span>
                                    <span class="block text-[10px] text-gray-400 font-mono mt-0.5">{{ $permission->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>