<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\EmailLog;

class QueueMonitorController extends Controller
{
    public function index()
    {
        // 1. Pending Emails (Jo line mein hain)
        $pendingJobs = DB::table('jobs')->count();

        // 2. Failed Emails (Jo fail ho gayin)
        $failedJobs = DB::table('failed_jobs')->count();

        // 3. Recent Logs (Jo emails ja chuki hain)
        $recentEmails = EmailLog::latest()->take(50)->get();

        // 4. Latest Failed Job Detail (Agar koi fail hui ho)
        // $lastFailed = DB::table('failed_jobs')->latest()->first();
        $lastFailed = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->first();

        return view('admin.queue-monitor', compact('pendingJobs', 'failedJobs', 'recentEmails', 'lastFailed'));
    }

    // Failed email ko dobara try karne ka button
    public function retryFailed()
    {
        \Artisan::call('queue:retry all');
        return back()->with('success', 'Failed emails ko dobara queue mein daal diya gaya hai.');
    }
}
