<?php

namespace App\Providers;

use DevDojo\Themes\ThemesServiceProvider as BaseThemesServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;

class ThemesServiceProvider extends BaseThemesServiceProvider
{
    /**
     * Theme pages are registered via explicit Livewire routes instead of Folio.
     */
    public function boot()
    {
        try {
            $theme = '';

            if (Schema::hasTable('themes')) {
                $theme = $this->rescue(function () {
                    return \DevDojo\Themes\Models\Theme::where('active', '=', 1)->first();
                });
                if (Cookie::get('theme')) {
                    $theme_cookied = \DevDojo\Themes\Models\Theme::where('folder', '=', Cookie::get('theme'))->first();
                    if (isset($theme_cookied->id)) {
                        $theme = $theme_cookied;
                    }
                }
            }

            view()->share('theme', $theme);

            $folder = config('themes.folder', resource_path('themes'));

            $this->loadThemeMiddleware($folder, $theme);
            $this->registerThemeBladeComponents($theme);

            if (isset($theme)) {
                $this->loadViewsFrom($folder.'/'.@$theme->folder, 'theme');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function registerThemeBladeComponents($theme): void
    {
        Blade::anonymousComponentPath(config('themes.folder').'/'.$theme->folder.'/components/elements');
        Blade::anonymousComponentPath(config('themes.folder').'/'.$theme->folder.'/components');
    }

    protected function loadThemeMiddleware($folder, $theme): void
    {
        if (empty($theme)) {
            return;
        }

        $middleware_folder = $folder.'/'.$theme->folder.'/middleware';
        if (file_exists($middleware_folder)) {
            $middleware_files = scandir($middleware_folder);
            foreach ($middleware_files as $middleware) {
                if ($middleware != '.' && $middleware != '..') {
                    include $middleware_folder.'/'.$middleware;
                    $middleware_classname = 'Themes\\Middleware\\'.str_replace('.php', '', $middleware);
                    if (class_exists($middleware_classname)) {
                        $this->app->make('Illuminate\Contracts\Http\Kernel')->prependMiddleware($middleware_classname);
                    }
                }
            }
        }
    }
}
