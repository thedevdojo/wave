<?php

namespace Wave\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wave\ActivityLog;

class CreateActivityLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public array $data
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // The user may have been force deleted before the worker got to this job
        $userModel = config('wave.user_model');
        if (! $userModel::withoutGlobalScopes()->whereKey($this->data['user_id'])->exists()) {
            return;
        }

        ActivityLog::create($this->data);
    }
}
