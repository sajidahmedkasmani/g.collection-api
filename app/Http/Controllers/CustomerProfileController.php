<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerProfileController extends Controller
{
    public function edit()
    {
        return view('customer.profile.edit', ['customer' => Auth::guard('customer')->user()]);
    }

    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:customers,email,' . $customer->id],
            'phone'    => ['required', 'string', 'max:20'],
            'city'     => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $customer->fill([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'city'  => $data['city'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $customer->password = Hash::make($data['password']);
        }

        $customer->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}