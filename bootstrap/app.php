<?php

use App\Providers\AppServiceProvider;
use DevDojo\Themes\ThemesServiceProvider;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Lab404\Impersonate\ImpersonateServiceProvider;
use RalphJSmit\Livewire\Urls\Middleware\LivewireUrlsMiddleware;
use Wave\WaveServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        ImpersonateServiceProvider::class,
        WaveServiceProvider::class,
        ThemesServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(AppServiceProvider::HOME);

        $middleware->encryptCookies(except: [
            'theme',
        ]);
        $middleware->preventRequestForgery(except: [
            '/webhook/paddle',
            '/webhook/stripe',
        ]);

        $middleware->append(DisableBladeIconComponents::class);

        $middleware->web(LivewireUrlsMiddleware::class);

        $middleware->throttleApi();

        // Only answer to APP_URL's host (and its subdomains), so links in emails can't point elsewhere. Skipped in local and tests
        $middleware->trustHosts();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
