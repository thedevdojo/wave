<?php

namespace Wave;

use Wave\TokenGuard;
use Livewire\Livewire;
use Illuminate\Routing\Router;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Gate;
use Wave\Facades\Wave as WaveFacade;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Laravel\Folio\Folio;
use Illuminate\Support\Facades\File;

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

	    $waveMiddleware = [
	    	\Illuminate\Auth\Middleware\Authenticate::class,
    		\Wave\Http\Middleware\TrialEnded::class,
    		\Wave\Http\Middleware\Cancelled::class,
    	];


        $this->app->router->aliasMiddleware('paddle-webhook-signature', \Wave\Http\Middleware\VerifyPaddleWebhookSignature::class);
    	$this->app->router->aliasMiddleware('token_api', \Wave\Http\Middleware\TokenMiddleware::class);
	    $this->app->router->pushMiddlewareToGroup('web', \Wave\Http\Middleware\WaveMiddleware::class);
        $this->app->router->pushMiddlewareToGroup('web', \Wave\Http\Middleware\InstallMiddleware::class);

	    $this->app->router->middlewareGroup('wave', $waveMiddleware);

        
	}

	public function boot(Router $router, Dispatcher $event){
		Relation::morphMap([
		    'users' => config('wave.user_model')
		]);

		if(!config('wave.show_docs')){
			Gate::define('viewLarecipe', function($user, $documentation) {
	            	return true;
	        });
	    }

        $this->loadViewsFrom(__DIR__.'/../docs/', 'docs');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wave');
        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));
        $this->loadBladeDirectives();

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

    // private function excludeInactiveThemes(){

    //     $theme = Theme::where('active', 1)->latest()->first();
    //     $activeTheme = $theme->folder;

    //     $viewFinder = $this->app['view']->getFinder();
        
    //     // Get the default view paths
    //     $paths = $viewFinder->getPaths();
        
    //     // Remove the default resources/views path
    //     $defaultViewPath = resource_path('views');
    //     if (($key = array_search($defaultViewPath, $paths)) !== false) {
    //         unset($paths[$key]);
    //     }

    //     // Get all subfolders inside resources/views
    //     $subfolders = File::directories($defaultViewPath);

    //     foreach ($subfolders as $folder) {
    //         $folderName = basename($folder);
            
    //         // Check if it's the themes folder
    //         if ($folderName === 'themes') {
    //             $themeFolders = File::directories($folder);

    //             foreach ($themeFolders as $themeFolder) {
    //                 $themeName = basename($themeFolder);

    //                 // Only add the active theme folder
    //                 if ($themeName === $activeTheme) {
    //                     $paths[] = $themeFolder;
    //                 }
    //             }
    //         } else {
    //             // Add other folders inside resources/views
    //             $paths[] = $folder;
    //         }
    //     }

    //     // Set the new view paths
    //     $viewFinder->setPaths($paths);

    //     // because we are changing the resources/views path we need to dynamically register the resources/views/components Blade path
    //     Blade::anonymousComponentPath(base_path('resources/views/components'));
    // }

}
