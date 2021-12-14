<?php

namespace App\Providers;

use Wave\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier as StripeCashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment() == 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }
        $this->setSchemaDefaultLength();

        if (env('CASHIER_VENDOR') == 'stripe') {
            StripeCashier::useCustomerModel(User::class);

            if (env('CASHIER_STRIPE_CALCULATE_TAXES')) {
                StripeCashier::calculateTaxes();
            }
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        StripeCashier::ignoreMigrations();
    }

    private function setSchemaDefaultLength(): void
    {
        try {
            Schema::defaultStringLength(191);
        }
        catch (\Exception $exception){}
    }
}
