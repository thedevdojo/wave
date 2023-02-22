<?php

namespace HansSchouten\LaravelPageBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Exception;

class PublishDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagebuilder:publish-demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the demo theme.';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        Artisan::call('vendor:publish', [
            '--provider' => 'HansSchouten\LaravelPageBuilder\ServiceProvider',
            '--tag' => 'demo-theme'
        ]);

        Artisan::call('pagebuilder:publish-theme', ['theme' => 'demo']);

        $this->info("The demo theme has been published");
    }

}
