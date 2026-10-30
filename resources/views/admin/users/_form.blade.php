@php
    $selected = old('permissions', $userPermissions ?? []);
@endphp

{{-- ... name, email, password, is_active fields (pehle jese) ... --}}

<div>
    <h3 class="text-xs uppercase tracking-wider text-gray-500 mb-3">Permissions</h3>

    <div class="space-y-4">
        @foreach($permissions as $module => $modulePermissions)
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-medium capitalize">{{ $module }}</h4>
                    <button type="button"
                            class="text-xs text-blue-600 hover:underline"
                            onclick="
                                const box = this.closest('.border');
                                const boxes = box.querySelectorAll('input[type=checkbox]');
                                const allChecked = [...boxes].every(b => b.checked);
                                boxes.forEach(b => b.checked = !allChecked);
                            ">
                        Toggle all
                    </button>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($modulePermissions as $permission)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->id }}"
                                   {{ in_array($permission->id, $selected) ? 'checked' : '' }}>
                            {{ $permission->label }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>