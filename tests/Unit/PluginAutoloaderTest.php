<?php

use Wave\Plugins\PluginAutoloader;

it('registers the plugin autoloader only once', function () {
    $before = spl_autoload_functions() ?: [];

    PluginAutoloader::register();
    PluginAutoloader::register();

    $after = spl_autoload_functions() ?: [];

    // Count closures originating from PluginAutoloader
    $pluginClosures = array_filter($after, function ($loader) {
        if ($loader instanceof Closure) {
            $ref = new ReflectionFunction($loader);
            return str_contains($ref->getFileName(), 'PluginAutoloader.php');
        }
        return false;
    });

    expect(count($pluginClosures))->toBe(1)
        ->and(count($after))->toBe(count($before) + 1);
});
