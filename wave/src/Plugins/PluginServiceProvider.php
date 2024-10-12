<?php

namespace Wave\Plugins;

use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PluginManager::class, function ($app) {
            return new PluginManager($app);
        });
    }

    public function boot()
    {
        $pluginManager = $this->app->make(PluginManager::class);
        $pluginManager->loadPlugins();
    }
}
