<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'bank_name' => 'nullable|string|max:100',
            'bank_account_title' => 'nullable|string|max:100',
            'bank_account_no' => 'nullable|string|max:50',
            'bank_iban' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        foreach ($data as $k => $v) {
            Setting::updateOrCreate(['key' => $k], ['value' => $v]);
        }

        return back()->with('success', 'Settings saved.');
    }
}