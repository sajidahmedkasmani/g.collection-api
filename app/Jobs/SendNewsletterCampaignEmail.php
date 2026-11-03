<?php

namespace App\Jobs;

use App\Mail\NewsletterCampaignMail;
use App\Models\EmailLog;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendNewsletterCampaignEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $campaignId,
        public int $subscriberId,
    ) {
    }

    public function handle(): void
    {
        $campaign = NewsletterCampaign::findOrFail($this->campaignId);
        $subscriber = NewsletterSubscriber::findOrFail($this->subscriberId);

        if (!$subscriber->isSubscribed()) {
            return;
        }

        try {
            Mail::to($subscriber->email)->send(new NewsletterCampaignMail($campaign, $subscriber));

            EmailLog::create([
                'recipient_email' => $subscriber->email,
                'subject' => $campaign->subject,
                'status' => 'sent',
            ]);
        } catch (Throwable $exception) {
            EmailLog::create([
                'recipient_email' => $subscriber->email,
                'subject' => $campaign->subject,
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
