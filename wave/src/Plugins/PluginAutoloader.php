<?php

namespace Wave\Plugins;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PluginAutoloader
{
    /**
     * Indicates if the autoloader has been registered.
     *
     * @var bool
     */
    protected static $registered = false;

    public static function register()
    {
        if (self::$registered) {
            return;
        }

        spl_autoload_register(function ($class) {
            $prefix = 'Wave\\Plugins\\';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            // Read after the prefix check: this runs for every class, even when no app is booted.
            $base_dir = config('wave.plugins_path').'/';

            $relative_class = substr($class, $len);
            $parts = explode('\\', $relative_class);

            if (count($parts) < 2) {
                return;
            }

            $plugin_name = $parts[0];
            $kebab_name = Str::kebab($plugin_name);
            $class_file = implode('/', array_slice($parts, 1)).'.php';

            $file = $base_dir.$kebab_name.'/'.$class_file;
            if (File::exists($file)) {
                require $file;

                return;
            }

            $src_file = $base_dir.$kebab_name.'/src/'.$class_file;
            if (File::exists($src_file)) {
                require $src_file;

                return;
            }
        });

        self::$registered = true;
    }
}
