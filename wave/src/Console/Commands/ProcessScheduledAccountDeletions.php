<?php

namespace Wave\Console\Commands;

use Illuminate\Console\Command;

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

                // Force delete (permanent deletion)
                $user->forceDelete();

                $this->info("Deleted account: {$email}");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to delete account {$user->email}: ".$e->getMessage());
            }
        }

        $this->info("Successfully deleted {$count} account(s).");

        return self::SUCCESS;
    }
}
