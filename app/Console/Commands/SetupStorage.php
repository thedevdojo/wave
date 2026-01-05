<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupStorage extends Command
{
    protected $signature = 'storage:setup';
    protected $description = 'Setup storage directories and symlinks for Wave';

    public function handle()
    {
        $this->info('Setting up storage directories...');

        // Create required directories
        $directories = [
            storage_path('app/public'),
            storage_path('app/public/posts'),
            storage_path('app/public/videos'),
            storage_path('app/public/attachments'),
            storage_path('app/public/livewire-tmp'),
        ];

        foreach ($directories as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
                $this->info("Created: {$dir}");
            }
        }

        // Create storage symlink
        $this->call('storage:link');

        $this->info('Storage setup complete!');
        return 0;
    }
}
