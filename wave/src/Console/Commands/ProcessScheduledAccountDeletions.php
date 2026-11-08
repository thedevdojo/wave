<?php

namespace Wave\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Wave\Actions\Billing\CancelSubscription;

class ProcessScheduledAccountDeletions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:process-deletions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled account deletions that have passed their grace period';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Processing scheduled account deletions...');

        $userModel = config('wave.user_model');

        // Find all users with deletion_scheduled_at in the past
        $usersToDelete = $userModel::whereNotNull('deletion_scheduled_at')
            ->where('deletion_scheduled_at', '<=', now())
            ->get();

        if ($usersToDelete->isEmpty()) {
            $this->info('No accounts scheduled for deletion.');

            return self::SUCCESS;
        }

        $count = 0;

        foreach ($usersToDelete as $user) {
            try {
                $email = $user->email;

                // Never delete someone who is still being billed, try again on the next run.
                $this->cancelSubscriptions($user);

                DB::transaction(function () use ($user) {
                    // These are morph or plain keyed, so there is no foreign key to cascade.
                    $user->subscriptions()->delete();
                    $user->profileKeyValues()->delete();
                    $user->notifications()->delete();
                    $user->emailVerificationCode()->delete();

                    $user->forceDelete();
                });

                $this->info("Deleted account: {$email}");
                $count++;
            } catch (\Throwable $e) {
                Log::error("Failed to delete account {$user->email}: ".$e->getMessage(), ['user_id' => $user->id]);
                $this->error("Failed to delete account {$user->email}: ".$e->getMessage());
            }
        }

        $this->info("Successfully deleted {$count} account(s).");

        return self::SUCCESS;
    }

    private function cancelSubscriptions($user): void
    {
        foreach ($user->subscriptions()->where('status', 'active')->get() as $subscription) {
            if (! app(CancelSubscription::class)($subscription)) {
                throw new RuntimeException("could not cancel {$subscription->vendor_slug} subscription {$subscription->vendor_subscription_id}");
            }
        }
    }
}
