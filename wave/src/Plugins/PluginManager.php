<?php

namespace Wave\Plugins;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PluginManager
{
    protected $app;

    protected $plugins = [];

    public function __construct(Application $app)
    {
        $this->app = $app;
        PluginAutoloader::register();
    }

    public function loadPlugins()
    {
        $installedPlugins = $this->getInstalledPlugins();

        // Only log when there are actually plugins to load
        if (!empty($installedPlugins)) {
            Log::info('Loading plugins: '.json_encode($installedPlugins));
        }

        foreach ($installedPlugins as $pluginName) {
            $studlyPluginName = Str::studly($pluginName);
            $pluginClass = "Wave\\Plugins\\{$studlyPluginName}\\{$studlyPluginName}Plugin";

            $expectedPath = $this->findPluginFile($pluginName);
            if ($expectedPath) {
                include_once $expectedPath;

                if (class_exists($pluginClass)) {
                    $plugin = new $pluginClass($this->app);
                    $this->plugins[$pluginName] = $plugin;
                    $this->app->register($plugin);
                    Log::info("Successfully loaded plugin: {$pluginClass}");
                } else {
                    Log::warning("Plugin class not found after including file: {$pluginClass}");
                }
            } else {
                Log::warning("Plugin file not found for: {$pluginName}");
            }
        }
    }

    protected function findPluginFile($pluginName)
    {
        $basePath = resource_path('plugins');
        $studlyName = Str::studly($pluginName);

        // Check for exact case match
        $exactPath = "{$basePath}/{$studlyName}/{$studlyName}Plugin.php";
        if (File::exists($exactPath)) {
            return $exactPath;
        }

        // Check for case-insensitive match
        $directories = File::directories($basePath);
        foreach ($directories as $directory) {
            if (strtolower(basename($directory)) === strtolower($pluginName)) {
                $filePath = "{$directory}/{$studlyName}Plugin.php";
                if (File::exists($filePath)) {
                    return $filePath;
                }
            }
        }

        return null;
    }

    protected function runPostActivationCommands(Plugin $plugin)
    {
        $commands = $plugin->getPostActivationCommands();

        foreach ($commands as $command) {
            if (is_string($command)) {
                Artisan::call($command);
            } elseif (is_callable($command)) {
                $command();
            }
        }
    }

    protected function getInstalledPlugins()
    {
        // Check if cache is available (not during package discovery)
        if ($this->app->bound('cache')) {
            try {
                return Cache::remember('wave_installed_plugins', 3600, function () {
                    $path = resource_path('plugins/installed.json');
                    if (! File::exists($path)) {
                        return [];
                    }

                    return File::json($path);
                });
            } catch (Exception $e) {
                // Fallback to direct file access if cache fails
            }
        }

        // Direct file access when cache is not available
        $path = resource_path('plugins/installed.json');
        if (! File::exists($path)) {
            return [];
        }

        return File::json($path);
    }
}
