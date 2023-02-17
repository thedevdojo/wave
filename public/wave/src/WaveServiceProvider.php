<?php

namespace Wave;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Wave\Facades\Wave as WaveFacade;
use Wave\TokenGuard;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

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

        // Subscription Directives

        Blade::directive('subscribed', function ($plan) {
            return "<?php if (!auth()->guest() && auth()->user()->subscribed($plan)) { ?>";
        });

        Blade::directive('notsubscribed', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endsubscribed', function () {
            return "<?php } ?>";
        });


        // Subscriber Directives

        Blade::directive('subscriber', function () {
            return "<?php if (!auth()->guest() && auth()->user()->subscriber()) { ?>";
        });

        Blade::directive('notsubscriber', function () {
            return "<?php } else { ?>";
        });

        Blade::directive('endsubscriber', function () {
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

    private function loadLivewireComponents(){
        Livewire::component('wave.settings.security', \Wave\Http\Livewire\Settings\Security::class);
        Livewire::component('wave.settings.api', \Wave\Http\Livewire\Settings\Api::class);
        Livewire::component('wave.settings.plans', \Wave\Http\Livewire\Settings\Plans::class);
        Livewire::component('wave.settings.subscription', \Wave\Http\Livewire\Settings\Subscription::class);
        Livewire::component('wave.settings.invoices', \Wave\Http\Livewire\Settings\Invoices::class);
    }

}
