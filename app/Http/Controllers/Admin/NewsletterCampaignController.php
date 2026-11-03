<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Services\NewsletterCampaignDispatcher;
use Illuminate\Http\Request;

class NewsletterCampaignController extends Controller
{
    public function index()
    {
        $campaigns = NewsletterCampaign::latest()->paginate(20, ['*'], 'campaign_page');
        $subscribers = NewsletterSubscriber::whereNull('unsubscribed_at')
            ->latest('subscribed_at')
            ->paginate(25, ['*'], 'subscriber_page');
        $subscriberCount = NewsletterSubscriber::whereNull('unsubscribed_at')->count();

        return view('admin.newsletters.index', compact('campaigns', 'subscribers', 'subscriberCount'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'subject' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string', 'max:30000'],
            'frequency' => ['required', 'in:once,weekly,monthly'],
            'next_send_at' => ['required', 'date', 'after_or_equal:now'],
        ]);
        $data['is_active'] = true;

        NewsletterCampaign::create($data);

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'Newsletter campaign scheduled.');
    }

    public function sendNow(NewsletterCampaign $campaign, NewsletterCampaignDispatcher $dispatcher)
    {
        $campaign->update([
            'is_active' => true,
            'next_send_at' => now(),
        ]);
        $count = $dispatcher->dispatchDue($campaign->id);

        return back()->with('success', "Newsletter queued for {$count} subscribed email(s).");
    }
}
