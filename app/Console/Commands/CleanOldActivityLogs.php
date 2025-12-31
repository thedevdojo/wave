<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class CleanOldActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:clean {--days= : Number of days to retain logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old activity logs based on retention period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! config('activity.enabled', true)) {
            $this->info('Activity logging is disabled.');

            return 0;
        }

        $days = $this->option('days') ?? config('activity.retention_days', 90);

        if (is_null($days)) {
            $this->info('No retention period set. Logs will be kept indefinitely.');

            return 0;
        }

        $cutoffDate = now()->subDays($days);

        $count = ActivityLog::where('created_at', '<', $cutoffDate)->count();

        if ($count === 0) {
            $this->info('No old activity logs to clean up.');

            return 0;
        }

        // Skip confirmation if non-interactive
        $shouldDelete = $this->option('no-interaction')
            || $this->confirm("Delete {$count} activity logs older than {$days} days?", true);

        if ($shouldDelete) {
            ActivityLog::where('created_at', '<', $cutoffDate)->delete();
            $this->info("Successfully deleted {$count} old activity logs.");
        } else {
            $this->info('Cleanup cancelled.');
        }

        return 0;
    }
}
