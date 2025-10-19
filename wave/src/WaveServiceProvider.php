<?php

namespace Wave;

use Wave\Http\Middleware\VerifyPaddleWebhookSignature;
use Wave\Http\Middleware\Subscribed;
use Wave\Http\Middleware\TokenMiddleware;
use Wave\Http\Middleware\InstallMiddleware;
use Wave\Http\Middleware\ThemeDemoMiddleware;
use Exception;
use Wave\Console\Commands\CancelExpiredSubscriptions;
use Wave\Console\Commands\CreatePluginCommand;
use App\Models\Forms;
use Wave\Http\Livewire\Billing\Checkout;
use Wave\Http\Livewire\Billing\Update;
use DevDojo\Themes\Models\Theme;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Vite as BaseVite;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Laravel\Folio\Folio;
use Livewire\Livewire;
use Wave\Facades\Wave as WaveFacade;
use Wave\Overrides\Vite;
use Wave\Plugins\PluginServiceProvider;

class WaveServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $loader = AliasLoader::getInstance();
        $loader->alias('Wave', WaveFacade::class);

        $this->app->singleton('wave', function () {
            return new Wave;
        });

        // Register Intervention Image Manager
        $this->app->singleton('image', function () {
            return new ImageManager(new Driver());
        });

        // Move helper loading to boot method to avoid cache service dependency

        $this->loadLivewireComponents();

        $this->app->router->aliasMiddleware('paddle-webhook-signature', VerifyPaddleWebhookSignature::class);
        $this->app->router->aliasMiddleware('subscribed', Subscribed::class);
        $this->app->router->aliasMiddleware('token_api', TokenMiddleware::class);

        if (! $this->hasDBConnection()) {
            $this->app->router->pushMiddlewareToGroup('web', InstallMiddleware::class);
        }

        if (config('wave.demo')) {
            $this->app->router->pushMiddlewareToGroup('web', ThemeDemoMiddleware::class);
            // Overwrite the Vite asset helper so we can use the demo folder as opposed to the build folder
            $this->app->singleton(BaseVite::class, function ($app) {
                // Replace the default Vite instance with the custom one
                return new Vite;
            });
        }

        // Register the PluginServiceProvider
        $this->app->register(PluginServiceProvider::class);
    }

    public function boot(Router $router, Dispatcher $event): void
    {

        Relation::morphMap([
            'users' => config('wave.user_model'),
        ]);

        $this->registerFilamentComponentsFriendlyNames();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wave');
        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));
        $this->loadBladeDirectives();
        $this->loadHelpers();
        $this->setDefaultThemeColors();

        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => config('wave.primary_color'),
            'success' => Color::Green,
            'warning' => Color::Amber,
        ]);

        Validator::extend('imageable', function ($attribute, $value, $params, $validator) {
            try {
                $manager = new ImageManager(new Driver());
                $manager->read($value);

                return true;
            } catch (Exception $e) {
                return false;
            }
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                CancelExpiredSubscriptions::class,
                CreatePluginCommand::class,
            ]);
            // $this->excludeInactiveThemes();
        }

        Relation::morphMap([
            'user' => config('auth.providers.model'),
            'form' => Forms::class,
            // Add other mappings as needed
        ]);

        $this->registerWaveFolioDirectory();
        $this->registerWaveComponentDirectory();
    }

    protected function loadHelpers()
    {
        // Check if cache service is available (not during package discovery)
        if ($this->app->bound('cache') && $this->app->make('cache')->getStore()) {
            try {
                $helpers = Cache::rememberForever('wave_helpers', function () {
                    return glob(__DIR__.'/Helpers/*.php');
                });
            } catch (Exception $e) {
                // Fallback to direct file loading if cache fails
                $helpers = glob(__DIR__.'/Helpers/*.php');
            }
        } else {
            // Direct file loading during package discovery or when cache is not available
            $helpers = glob(__DIR__.'/Helpers/*.php');
        }

        foreach ($helpers as $filename) {
            require_once $filename;
        }
    }

    protected function loadMiddleware()
    {
        foreach (glob(__DIR__.'/Http/Middleware/*.php') as $filename) {
            require_once $filename;
        }
    }

    protected function loadBladeDirectives()
    {

        // app()->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
        // @admin directives
        Blade::if('admin', function () {
            return ! auth()->guest() && auth()->user()->isAdmin();
        });

        // @subscriber directives
        Blade::if('subscriber', function () {
            return ! auth()->guest() && auth()->user()->subscriber();
        });

        // @notsubscriber directives
        Blade::if('notsubscriber', function () {
            return ! auth()->guest() && ! auth()->user()->subscriber();
        });

        // Subscribed Directives
        Blade::if('subscribed', function ($plan) {
            return ! auth()->guest() && auth()->user()->subscribedToPlan($plan);
        });

        // home directives
        Blade::if('home', function () {
            return request()->is('/');
        });

    }

    protected function registerFilamentComponentsFriendlyNames()
    {
        // Blade::component('filament::components.avatar', 'avatar');
        Blade::component('filament::components.dropdown.index', 'dropdown');
        Blade::component('filament::components.dropdown.list.index', 'dropdown.list');
        Blade::component('filament::components.dropdown.list.item', 'dropdown.list.item');
    }

    protected function registerWaveFolioDirectory()
    {
        if (File::exists(base_path('wave/resources/views/pages'))) {
            Folio::path(base_path('wave/resources/views/pages'))->middleware([
                '*' => [
                    //
                ],
            ]);
        }
    }

    protected function registerWaveComponentDirectory()
    {
        Blade::anonymousComponentPath(base_path('wave/resources/views/components'));
    }

    private function loadLivewireComponents()
    {
        Livewire::component('billing.checkout', Checkout::class);
        Livewire::component('billing.update', Update::class);
    }

    protected function setDefaultThemeColors()
    {
        if (config('wave.demo')) {
            $color = '#000000'; // Default color

            // Only use cache if available
            if ($this->app->bound('cache') && $this->hasDBConnection()) {
                try {
                    $cacheKey = 'wave_theme_color_' . Cookie::get('theme', 'default');
                    $color = Cache::remember($cacheKey, 3600, function () {
                        $theme = $this->getActiveTheme();

                        if (isset($theme->id)) {
                            if (Cookie::get('theme')) {
                                $theme_cookied = Theme::where('folder', '=', Cookie::get('theme'))->first();
                                if (isset($theme_cookied->id)) {
                                    $theme = $theme_cookied;
                                }
                            }

                            return match ($theme->folder) {
                                'anchor' => '#000000',
                                'blank' => '#090909',
                                'cove' => '#0069ff',
                                'drift' => '#000000',
                                'fusion' => '#0069ff',
                                default => '#000000'
                            };
                        }

                        return '#000000';
                    });
                } catch (Exception $e) {
                    // Fallback to default color if cache or DB fails
                    $color = '#000000';
                }
            }

            Config::set('wave.primary_color', $color);
        }
    }

    protected function getActiveTheme()
    {
        if ($this->app->bound('cache') && $this->hasDBConnection()) {
            try {
                return Cache::remember('wave_active_theme', 3600, function () {
                    return \Wave\Theme::where('active', 1)->first();
                });
            } catch (Exception $e) {
                // Fallback to direct DB query if cache fails
                return \Wave\Theme::where('active', 1)->first();
            }
        }

        // Direct DB query when cache is not available
        if ($this->hasDBConnection()) {
            return \Wave\Theme::where('active', 1)->first();
        }

        return null;
    }

    protected function hasDBConnection()
    {
        $hasDatabaseConnection = true;

        try {
            DB::connection()->getPdo();
        } catch (Exception $e) {
            $hasDatabaseConnection = false;
        }

        return $hasDatabaseConnection;
    }
}
