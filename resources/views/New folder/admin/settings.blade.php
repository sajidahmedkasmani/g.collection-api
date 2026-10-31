@extends('admin.layout')
@section('title', 'Settings')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <h2 class="text-lg font-bold mb-4">Bank Transfer Details</h2>
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="grid gap-4 mb-4">
            <div>
                <label class="text-sm font-medium">Bank Name</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $settings['bank_name'] ?? '') }}" class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>
            <div>
                <label class="text-sm font-medium">Account Title</label>
                <input type="text" name="bank_account_title" value="{{ old('bank_account_title', $settings['bank_account_title'] ?? '') }}" class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>
            <div>
                <label class="text-sm font-medium">Account Number</label>
                <input type="text" name="bank_account_no" value="{{ old('bank_account_no', $settings['bank_account_no'] ?? '') }}" class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>
            <div>
                <label class="text-sm font-medium">IBAN</label>
                <input type="text" name="bank_iban" value="{{ old('bank_iban', $settings['bank_iban'] ?? '') }}" class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>
            <div>
                <label class="text-sm font-medium">WhatsApp Number</label>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>
        </div>
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm">Save Settings</button>
    </form>
</div>
@endsection