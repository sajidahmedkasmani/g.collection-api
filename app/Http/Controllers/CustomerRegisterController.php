<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            // 'email'    => ['required', 'email', 'unique:customers,email'],
            'email' => [
                'required',
                'email',
                Rule::unique('customers', 'email')->where(fn ($q) => $q->where('is_deleted', false)),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = Customer::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('customer.dashboard');
    }
}