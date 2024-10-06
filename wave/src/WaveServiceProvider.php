<?php

namespace Wave;

use Wave\TokenGuard;
use Livewire\Livewire;
use Laravel\Folio\Folio;
use Wave\Overrides\Vite;
use Illuminate\Routing\Router;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Wave\Facades\Wave as WaveFacade;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Vite as BaseVite;
use Illuminate\Database\Eloquent\Relations\Relation;

class WaveServiceProvider extends ServiceProvider
{

	public function register(){

	    $loader = AliasLoader::getInstance();
	    $loader->alias('Wave', WaveFacade::class);

	    $this->app->singleton('wave', function () {
	        return new Wave();
	    });

	    $this->loadHelpers();

        $this->loadLivewireComponents();

        $this->app->router->aliasMiddleware('paddle-webhook-signature', \Wave\Http\Middleware\VerifyPaddleWebhookSignature::class);
    	$this->app->router->aliasMiddleware('subscribed', \Wave\Http\Middleware\Subscribed::class);
        $this->app->router->aliasMiddleware('token_api', \Wave\Http\Middleware\TokenMiddleware::class);
        
        if(!$this->hasDBConnection()){
            $this->app->router->pushMiddlewareToGroup('web', \Wave\Http\Middleware\InstallMiddleware::class);
        }

        if(config('wave.demo')){
            $this->app->router->pushMiddlewareToGroup('web', \Wave\Http\Middleware\ThemeDemoMiddleware::class);
            // Overwrite the Vite asset helper so we can use the demo folder as opposed to the build folder
            $this->app->singleton(BaseVite::class, function ($app) {
                // Replace the default Vite instance with the custom one
                return new Vite();
            });
        }
	}

	public function boot(Router $router, Dispatcher $event){

		Relation::morphMap([
		    'users' => config('wave.user_model')
		]);

        $this->registerFilamentComponentsFriendlyNames();

		if(!config('wave.show_docs')){
			Gate::define('viewLarecipe', function($user, $documentation) {
	            	return true;
	        });
	    }

        $this->loadViewsFrom(__DIR__.'/../docs/', 'docs');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wave');
        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));
        $this->loadBladeDirectives();
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
                ImageManagerStatic::make($value);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Wave\Console\Commands\CancelExpiredSubscriptions::class,
            ]);
            //$this->excludeInactiveThemes();
        }

        Relation::morphMap([
            'user' => config('auth.providers.model'),
            'form' => \App\Models\Forms::class
            // Add other mappings as needed
        ]);

        $this->registerWaveFolioDirectory();
        $this->registerWaveComponentDirectory();
	}

	protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    protected function loadMiddleware()
    {
        foreach (glob(__DIR__.'/Http/Middleware/*.php') as $filename) {
            require_once $filename;
        }
    }

    protected function loadBladeDirectives(){

        Blade::directive('admin', function ($plan) {
            return "<?php if (!auth()->guest() && auth()->user()->isAdmin()) { ?>";
        });

        Blade::directive('elsenotadmin', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endadmin', function ($plan) {
            return "<?php } ?>";
        });

        // Subscription Directives

        Blade::directive('subscribed', function ($plan) {
            return "<?php if (!auth()->guest() && auth()->user()->subscribed($plan)) { ?>";
        });

        Blade::directive('elsenotsubscribed', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endsubscribed', function () {
            return "<?php } ?>";
        });


        // Subscriber Directives

        Blade::directive('subscriber', function () {
            return "<?php if (!auth()->guest() && auth()->user()->subscriber()) { ?>";
        });

        Blade::directive('elsenotsubscriber', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endsubscriber', function () {
            return "<?php } ?>";
        });


        Blade::directive('notsubscriber', function () {
            return "<?php if (!auth()->guest() && !auth()->user()->subscriber()) { ?>";
        });

        Blade::directive('elsesubscriber', function () {
            return "<?php } else { ?>";
        });
        
        Blade::directive('endnotsubscriber', function () {
            return "<?php } ?>";
        });

        


        // Trial Directives

        Blade::directive('trial', function ($plan) {
            return "<?php if (!auth()->guest() && auth()->user()->onTrial()) { ?>";
        });

        Blade::directive('nottrial', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endtrial', function () {
            return "<?php } ?>";
        });

        // home Directives

        Blade::directive('home', function () {
            $isHomePage = false;

            // check if we are on the homepage
            if ( request()->is('/') ) {
                $isHomePage = true;
            }

            return "<?php if ($isHomePage) { ?>";
        });

        Blade::directive('nothome', function(){
            return "<?php } else { ?>";
        });


        Blade::directive('endhome', function () {
            return "<?php } ?>";
        });


        Blade::directive('waveCheckout', function(){
            return '{!! view("wave::checkout")->render() !!}';
        });

        // role Directives

        Blade::directive('role', function ($role) {
            return "<?php if (!auth()->guest() && auth()->user()->hasRole($role)) { ?>";
        });

        Blade::directive('notrole', function () {
            return "<?php } else { ?>";
        });


        Blade::directive('endrole', function () {
            return "<?php } ?>";
        });
    }

    protected function registerFilamentComponentsFriendlyNames(){
        // Blade::component('filament::components.avatar', 'avatar');
        Blade::component('filament::components.dropdown.index', 'dropdown');
		Blade::component('filament::components.dropdown.list.index', 'dropdown.list');
		Blade::component('filament::components.dropdown.list.item', 'dropdown.list.item');
    }

    protected function registerWaveFolioDirectory(){
        if (File::exists(base_path('wave/resources/views/pages'))) {
            Folio::path(base_path('wave/resources/views/pages'))->middleware([
                '*' => [
                    //
                ],
            ]);
        }
    }

    protected function registerWaveComponentDirectory(){
        Blade::anonymousComponentPath(base_path('wave/resources/views/components'));
    }

    private function loadLivewireComponents(){
        Livewire::component('billing.checkout', \Wave\Http\Livewire\Billing\Checkout::class);
        Livewire::component('billing.update', \Wave\Http\Livewire\Billing\Update::class);
    }

    protected function setDefaultThemeColors(){
        if(config('wave.demo')){
            $theme = $this->getActiveTheme();

            if(isset($theme->id)){
                if(Cookie::get('theme')){
                    $theme_cookied = \DevDojo\Themes\Models\Theme::where('folder', '=', Cookie::get('theme'))->first();
                    if(isset($theme_cookied->id)){
                        $theme = $theme_cookied;
                    }
                }

                $default_theme_color = match($theme->folder){
                    'anchor' => '#000000',
                    'blank' => '#090909',
                    'cove' => '#0069ff',
                    'drift' => '#000000',
                    'fusion' => '#0069ff'
                };

                Config::set('wave.primary_color', $default_theme_color);
            }
        }
    }

    protected function getActiveTheme(){
        return \Wave\Theme::where('active', 1)->first();
    }

    protected function hasDBConnection(){
        $hasDatabaseConnection = true;

        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $hasDatabaseConnection = false;
        }

        if (!$hasDatabaseConnection) {
            // Code to execute if there is no database connection
            echo "Database connection failed!";
        }

        return $hasDatabaseConnection;
    }

}
