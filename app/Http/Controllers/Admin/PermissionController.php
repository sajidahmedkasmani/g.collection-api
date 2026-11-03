<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');

        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z][a-z0-9_-]*(\\.[a-z][a-z0-9_-]*)+$/',
                Rule::unique('permissions', 'name'),
            ],
            'label' => ['required', 'string', 'max:120'],
            'module' => ['required', 'string', 'max:100'],
        ]);

        Permission::create($data + ['is_active' => true]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created and activated.');
    }

    public function toggleActive(Permission $permission)
    {
        $permission->update(['is_active' => !$permission->is_active]);

        return back()->with(
            'success',
            $permission->is_active
                ? 'Permission activated.'
                : 'Permission deactivated. It no longer grants access to any user.'
        );
    }
}
