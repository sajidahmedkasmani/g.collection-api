@extends('admin.layout')
@section('title', 'Email & Queue Monitor')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-medium">Pending Emails</h3>
            <p class="text-3xl font-bold">{{ $pendingJobs }}</p>
            <p class="text-xs text-gray-400 mt-1">Queue mein bhejne ke liye bachi hui hain</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium">Recently Sent</h3>
            <p class="text-3xl font-bold">{{ $recentEmails->where('status', 'sent')->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">Last 50 emails mein se</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm font-medium">Failed Emails</h3>
            <p class="text-3xl font-bold text-red-600">{{ $failedJobs }}</p>
            @if($failedJobs > 0)
                @can('queue.retry')
                <form action="{{ route('admin.queue.retry') }}" method="POST" class="mt-2">
                    @csrf
                    <button class="bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-red-700">Retry All</button>
                </form>
                @endcan
            @endif
        </div>
    </div>

    <!-- Recent Emails Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Email Logs</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-3 px-6">Recipient</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Error (If any)</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEmails as $log)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-6 font-medium">{{ $log->recipient_email }}</td>
                        <td>{{ $log->subject }}</td>
                        <td>
                            @if($log->status === 'sent')
                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Sent</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Failed</span>
                            @endif
                        </td>
                        <td class="text-xs text-red-500">{{ $log->error_message ?? '-' }}</td>
                        <td class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-gray-500">No emails sent yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection