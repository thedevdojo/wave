<?php

namespace HansSchouten\LaravelPageBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Exception;

class CreateTheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagebuilder:create-theme {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new theme.';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        $themeName = $this->argument('name');
        $themePath = base_path(config('pagebuilder.theme.folder_url') . '/' . $themeName);
        File::copyDirectory(__DIR__ . '/../../themes/stub', $themePath);
        Artisan::call('pagebuilder:publish-theme', ['theme' => $themeName]);
    }

}
