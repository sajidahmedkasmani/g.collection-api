<?php

namespace App\Console\Commands;

use App\Services\NewsletterCampaignDispatcher;
use Illuminate\Console\Command;

class DispatchScheduledNewsletters extends Command
{
    protected $signature = 'newsletter:dispatch-due';

    protected $description = 'Queue newsletter campaigns that are due to send';

    public function handle(NewsletterCampaignDispatcher $dispatcher): int
    {
        $count = $dispatcher->dispatchDue();
        $this->info("Queued {$count} newsletter email(s).");

        return self::SUCCESS;
    }
}
