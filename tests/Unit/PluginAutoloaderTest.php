<?php

use Wave\Plugins\PluginAutoloader;

it('registers the plugin autoloader only once', function () {
    PluginAutoloader::register();
    PluginAutoloader::register();

    // Count closures originating from PluginAutoloader. An earlier test in the
    // same process may already have registered it, so don't compare totals.
    $pluginClosures = array_filter(spl_autoload_functions() ?: [], function ($loader) {
        if ($loader instanceof Closure) {
            $ref = new ReflectionFunction($loader);

            return str_contains($ref->getFileName(), 'PluginAutoloader.php');
        }

        return false;
    });

    expect(count($pluginClosures))->toBe(1);
});
