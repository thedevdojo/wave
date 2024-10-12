<?php

namespace Wave\Plugins;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PluginAutoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $prefix = 'Wave\\Plugins\\';
            $base_dir = resource_path('plugins/');

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relative_class = substr($class, $len);
            $parts = explode('\\', $relative_class);

            if (count($parts) < 2) {
                return;
            }

            $plugin_name = $parts[0];
            $kebab_name = Str::kebab($plugin_name);
            $class_file = implode('/', array_slice($parts, 1)) . '.php';

            $file = $base_dir . $kebab_name . '/' . $class_file;
            if (File::exists($file)) {
                require $file;
                return;
            }

            $src_file = $base_dir . $kebab_name . '/src/' . $class_file;
            if (File::exists($src_file)) {
                require $src_file;
                return;
            }
        });
    }
}