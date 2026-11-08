<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class VoltServiceProvider extends ServiceProvider
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
        Volt::mount([
            config('livewire.view_path', resource_path('views/livewire')),
            resource_path('views/pages'),
        ]);

        // Volt >= 1.11 only resolves anonymous @volt fragments from files inside a mounted
        // path or one of the view paths. Our theme Folio pages and Wave core views live
        // outside of those, so allow them here without mounting them as Volt components.
        config(['view.paths' => array_values(array_unique(array_merge(config('view.paths', []), [
            config('themes.folder', resource_path('themes')),
            base_path('wave/resources/views'),
        ])))]);
    }
}
