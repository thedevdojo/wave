<?php

namespace Wave\Plugins;

use Illuminate\Support\ServiceProvider;

abstract class Plugin extends ServiceProvider
{
    protected $name;

    public function getName()
    {
        return $this->name;
    }

    // Provide default implementations
    public function register()
    {
        // Default register logic, if any
        // Can be overridden by specific plugins
    }

    public function boot()
    {
        // Default boot logic, if any
        // Can be overridden by specific plugins
    }

    // You can add additional methods that plugins should implement
    abstract public function getPluginInfo(): array;

    public function postActivation()
    {
        // Default implementation (empty)
    }
}
