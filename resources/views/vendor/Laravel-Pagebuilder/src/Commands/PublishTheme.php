<?php

namespace HansSchouten\LaravelPageBuilder\Commands;

use Illuminate\Console\Command;
use Exception;

class PublishTheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagebuilder:publish-theme {theme}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create for the given theme a folder in /public and symlink to the public folder of the theme.';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        $theme = basename($this->argument('theme'));

        $themePublicFolder = config('pagebuilder.theme.folder') . "/{$theme}/public";
        if (! file_exists($themePublicFolder)) {
            return $this->error("The folder {$themePublicFolder} does not exist.");
        }

        if (! file_exists(public_path("themes"))) {
            mkdir(public_path("themes"));
        }

        $publicThemeFolder = public_path("themes/{$theme}");
        if (file_exists($publicThemeFolder)) {
            return $this->error( "The 'public/{$theme}' directory already exists.");
        }

        $this->laravel->make('files')->link(
            $themePublicFolder, $publicThemeFolder
        );

        return $this->info("The public directory of the {$theme} theme has been linked.");
    }

}
