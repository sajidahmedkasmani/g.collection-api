<?php

namespace App\Services;

use App\Jobs\SendNewsletterCampaignEmail;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\DB;

class NewsletterCampaignDispatcher
{
    public function dispatchDue(?int $campaignId = null): int
    {
        $campaignQuery = NewsletterCampaign::query()
            ->where('is_active', true)
            ->whereNotNull('next_send_at')
            ->where('next_send_at', '<=', now())
            ->when($campaignId, fn ($query) => $query->whereKey($campaignId))
            ->orderBy('id');

        $campaignIds = $campaignQuery->pluck('id');
        $dispatched = 0;

        foreach ($campaignIds as $id) {
            $campaign = DB::transaction(function () use ($id) {
                $campaign = NewsletterCampaign::whereKey($id)->lockForUpdate()->first();
                if (!$campaign || !$campaign->is_active || !$campaign->next_send_at || $campaign->next_send_at->isFuture()) {
                    return null;
                }

                $sentAt = now();
                $nextSendAt = match ($campaign->frequency) {
                    'weekly' => $campaign->next_send_at->copy()->addWeek(),
                    'monthly' => $campaign->next_send_at->copy()->addMonthNoOverflow(),
                    default => null,
                };

                if ($nextSendAt && $nextSendAt->lte($sentAt)) {
                    $nextSendAt = $campaign->frequency === 'weekly'
                        ? $sentAt->copy()->addWeek()
                        : $sentAt->copy()->addMonthNoOverflow();
                }

                $campaign->update([
                    'last_sent_at' => $sentAt,
                    'next_send_at' => $nextSendAt,
                    'is_active' => $nextSendAt !== null,
                ]);

                return $campaign;
            });

            if (!$campaign) {
                continue;
            }

            NewsletterSubscriber::whereNull('unsubscribed_at')
                ->select('id')
                ->chunkById(500, function ($subscribers) use ($campaign, &$dispatched) {
                    foreach ($subscribers as $subscriber) {
                        SendNewsletterCampaignEmail::dispatch($campaign->id, $subscriber->id);
                        $dispatched++;
                    }
                });
        }

        return $dispatched;
    }
}
