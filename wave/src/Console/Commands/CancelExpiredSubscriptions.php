<?php

namespace Wave\Console\Commands;

use Illuminate\Console\Command;
use Wave\Subscription;
use Carbon\Carbon;

class CancelExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:cancel-expired';
    protected $description = 'Cancel subscriptions that have expired';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();

        // Find subscriptions where ends_at is past the current date and status is active
        $subscriptions = Subscription::where('status', 'active')
            ->where('ends_at', '<', $now)
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->cancel();
            $this->info('Subscription ID ' . $subscription->id . ' has been cancelled.');
        }

        $this->info('Checked all subscriptions.');
    }
}