<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register our views directory
        $this->loadViewsFrom(resource_path('views'), 'supapost');
        
        // Add our navigation to the Wave navigation
        View::composer('wave::layouts.app', function ($view) {
            // Get the original sidebar data
            $sidebar = $view->getData()['sidebar'] ?? [];
            
            // Add our navigation partial
            $customNav = View::make('partials.navigation')->render();
            
            // Append our navigation to the sidebar
            if (is_string($sidebar)) {
                $sidebar .= $customNav;
            }
            
            // Share the updated sidebar with the view
            $view->with('sidebar', $sidebar);
        });
    }
}
