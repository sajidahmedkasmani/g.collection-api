<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('permissions')->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('label')->get()->groupBy('module');

        return view('admin.users.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'is_active'     => ['boolean'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $permissions = Permission::orderBy('module')->orderBy('label')->get()->groupBy('module');
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'permissions', 'userPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email,' . $user->id],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active'     => ['boolean'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $user->fill([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'is_active' => $request->boolean('is_active'),
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $user->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete(); // pivot cascade delete ho jayega

        return back()->with('success', 'User deleted successfully.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'User status updated.');
    }
}