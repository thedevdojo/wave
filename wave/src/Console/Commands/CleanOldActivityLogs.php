<?php

namespace Wave\Console\Commands;

use Illuminate\Console\Command;
use Wave\ActivityLog;

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
    public function handle(): int
    {
        if (! config('activity.enabled', true)) {
            $this->info('Activity logging is disabled.');

            return self::SUCCESS;
        }

        $days = $this->option('days') ?? config('activity.retention_days', 90);

        if (is_null($days)) {
            $this->info('No retention period set. Logs will be kept indefinitely.');

            return self::SUCCESS;
        }

        $cutoffDate = now()->subDays((int) $days);

        $count = ActivityLog::where('created_at', '<', $cutoffDate)->count();

        if ($count === 0) {
            $this->info('No old activity logs to clean up.');

            return self::SUCCESS;
        }

        $shouldDelete = $this->option('no-interaction')
            || $this->confirm("Delete {$count} activity logs older than {$days} days?", true);

        if ($shouldDelete) {
            ActivityLog::where('created_at', '<', $cutoffDate)->delete();
            $this->info("Successfully deleted {$count} old activity logs.");
        } else {
            $this->info('Cleanup cancelled.');
        }

        return self::SUCCESS;
    }
}
