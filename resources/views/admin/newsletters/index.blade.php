@extends('admin.layout')
@section('title', 'Newsletters')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-ink">Newsletter Campaigns</h1>
        <p class="text-sm text-gray-500 mt-1">{{ number_format($subscriberCount) }} active subscriber(s). Schedule one-time, weekly, or monthly sends.</p>
    </div>

    <form method="POST" action="{{ route('admin.newsletters.store') }}" class="bg-white rounded-2xl p-6 soft space-y-4">
        @csrf
        <h2 class="text-base font-bold">Create a campaign</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="campaign-title" class="block text-xs font-semibold mb-1.5">Internal campaign name</label>
                <input id="campaign-title" name="title" value="{{ old('title') }}" required maxlength="160" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="campaign-subject" class="block text-xs font-semibold mb-1.5">Email subject</label>
                <input id="campaign-subject" name="subject" value="{{ old('subject') }}" required maxlength="200" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                @error('subject') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label for="campaign-content" class="block text-xs font-semibold mb-1.5">Newsletter message</label>
            <textarea id="campaign-content" name="content" rows="8" required maxlength="30000" class="w-full border rounded-xl px-3 py-2.5 text-sm">{{ old('content') }}</textarea>
            @error('content') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label for="campaign-frequency" class="block text-xs font-semibold mb-1.5">Repeat</label>
                <select id="campaign-frequency" name="frequency" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    <option value="once" @selected(old('frequency', 'once') === 'once')>One time</option>
                    <option value="weekly" @selected(old('frequency') === 'weekly')>Weekly</option>
                    <option value="monthly" @selected(old('frequency') === 'monthly')>Monthly</option>
                </select>
                @error('frequency') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="campaign-next-send" class="block text-xs font-semibold mb-1.5">First send time</label>
                <input id="campaign-next-send" type="datetime-local" name="next_send_at" value="{{ old('next_send_at', now()->addMinutes(10)->format('Y-m-d\\TH:i')) }}" required class="w-full border rounded-xl px-3 py-2.5 text-sm">
                @error('next_send_at') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <p class="text-xs text-gray-500">Each subscriber receives an unsubscribe link. Messages are sent through the configured Laravel mail and queue drivers.</p>
        <button class="btn-bloom text-white px-5 py-2.5 rounded-xl text-xs font-semibold">Save campaign</button>
    </form>

    <div class="bg-white rounded-2xl soft overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold">Saved campaigns</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($campaigns as $campaign)
                <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="min-w-0">
                        <h3 class="font-semibold">{{ $campaign->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $campaign->subject }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ ucfirst($campaign->frequency) }} ·
                            @if($campaign->is_active)
                                Next: {{ $campaign->next_send_at?->format('d M Y, h:i A') ?? 'Not scheduled' }}
                            @else
                                Completed / paused
                            @endif
                            @if($campaign->last_sent_at) · Last sent {{ $campaign->last_sent_at->format('d M Y, h:i A') }} @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.newsletters.send-now', $campaign) }}">
                        @csrf
                        <button class="border border-plum text-plum px-4 py-2 rounded-full text-xs font-medium hover:bg-plum hover:text-white transition">
                            Send now
                        </button>
                    </form>
                </div>
            @empty
                <p class="p-8 text-center text-sm text-gray-500">No campaigns created yet.</p>
            @endforelse
        </div>
        <div class="p-4">{{ $campaigns->links() }}</div>
    </div>

    <div class="bg-white rounded-2xl soft overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold">Active subscribers</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($subscribers as $subscriber)
                <div class="px-5 py-3 flex items-center justify-between gap-4 text-sm">
                    <span class="truncate">{{ $subscriber->email }}</span>
                    <span class="shrink-0 text-xs text-gray-500">Subscribed {{ $subscriber->subscribed_at?->format('d M Y') }}</span>
                </div>
            @empty
                <p class="p-8 text-center text-sm text-gray-500">No active subscribers yet.</p>
            @endforelse
        </div>
        <div class="p-4">{{ $subscribers->links() }}</div>
    </div>
</div>
@endsection
