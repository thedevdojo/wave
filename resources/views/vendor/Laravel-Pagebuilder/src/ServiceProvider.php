<?php

namespace HansSchouten\LaravelPageBuilder;

use HansSchouten\LaravelPageBuilder\Commands\CreateTheme;
use HansSchouten\LaravelPageBuilder\Commands\PublishDemo;
use HansSchouten\LaravelPageBuilder\Commands\PublishTheme;
use PHPageBuilder\PHPageBuilder;
use Exception;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws Exception
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateTheme::class,
                PublishTheme::class,
                PublishDemo::class,
            ]);
        } elseif (empty(config('pagebuilder'))) {
            throw new Exception("No PHPageBuilder config found, please run: php artisan vendor:publish --provider=\"HansSchouten\LaravelPageBuilder\ServiceProvider\" --tag=config");
        }

        // register singleton phpPageBuilder (this ensures phpb_ helpers have the right config without first manually creating a PHPageBuilder instance)
        $this->app->singleton('phpPageBuilder', function($app) {
            return new PHPageBuilder(config('pagebuilder') ?? []);
        });
        $this->app->make('phpPageBuilder');

        $this->publishes([
            __DIR__ . '/../config/pagebuilder.php' => config_path('pagebuilder.php'),
        ], 'config');
        $this->publishes([
            __DIR__ . '/../themes/demo' => base_path(config('pagebuilder.theme.folder_url') . '/demo'),
        ], 'demo-theme');
    }
}
