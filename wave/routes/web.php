<?php

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;
use Wave\Actions\Reset;
use Wave\Page;

Route::impersonate();

Route::livewire('/dashboard', 'dashboard.index')->name('dashboard');
Route::livewire('/profile/{username}', 'profile.username')->name('wave.profile');

Route::livewire('/blog', 'blog.index')->name('blog');
Route::livewire('/blog/{category:slug}', 'blog.category.index')->name('blog.category');
Route::livewire('/blog/{category:slug}/{post:slug}', 'blog.category.post')->name('blog.post');

Route::livewire('/changelog', 'changelog.index')->name('changelogs');
Route::livewire('/changelog/{changelog}', 'changelog.show')->name('changelog');

Route::middleware('auth')->group(function () {
    Route::livewire('/notifications', 'notifications.index')->name('notifications');
    Route::livewire('/settings/profile', 'settings.profile')->name('settings.profile');
    Route::livewire('/settings/activity', 'settings.activity')->name('settings.activity');
    Route::livewire('/settings/security', 'settings.security')->name('settings.security');
    Route::livewire('/settings/subscription', 'settings.subscription')->name('settings.subscription');
    Route::livewire('/settings/api', 'settings.api')->name('settings.api');
    Route::livewire('/settings/privacy', 'settings.privacy')->name('settings.privacy');
    Route::livewire('/settings/deletion', 'settings.deletion')->name('settings.deletion');
    Route::livewire('/settings/export', 'settings.export')->name('settings.export');
    Route::livewire('/settings/social', 'settings.social')->name('settings.social');
    Route::livewire('/settings/notifications', 'settings.notifications')->name('settings.notifications');
    Route::livewire('/settings/invoices', 'settings.invoices')->name('settings.invoices');
    Route::livewire('/subscription/welcome', 'subscription.welcome')->name('subscription.welcome');
});

Route::get('logout', '\Wave\Http\Controllers\LogoutController@logout')->name('wave.logout');

Route::view('install', 'wave::install')->name('wave.install');

Route::group(['middleware' => 'auth'], function () {
    Route::redirect('settings', 'settings/profile')->name('settings');

    if (config('wave.billing_provider') == 'paddle') {
        Route::get('settings/invoices/{invoice}', '\Wave\Http\Controllers\SubscriptionController@invoice')->name('wave.paddle.invoice');
    }

    Route::post('notification/read/{id}', '\Wave\Http\Controllers\NotificationController@delete')->name('wave.notification.read');
    Route::post('changelog/read', '\Wave\Http\Controllers\ChangelogController@read')->name('changelog.read');

    Route::post('cancel', '\Wave\Http\Controllers\SubscriptionController@cancel')->name('wave.cancel');
    Route::view('checkout/welcome', 'theme::welcome');

    Route::post('subscribe', '\Wave\Http\Controllers\SubscriptionController@subscribe')->name('wave.subscribe');
    Route::post('switch-plans', '\Wave\Http\Controllers\SubscriptionController@switchPlans')->name('wave.switch-plans');
});

Route::get('wave/theme/image/{theme_name}', '\Wave\Http\Controllers\ThemeImageController@show');
Route::get('wave/plugin/image/{plugin_name}', '\Wave\Http\Controllers\PluginImageController@show');
Route::redirect('admin/login', '/auth/login');

if (app()->environment('local')) {
    Route::get('reset', Reset::class)->middleware('auth');
}

Route::post('webhook/paddle', '\Wave\Http\Controllers\Billing\Webhooks\PaddleWebhook@handler')->middleware('paddle-webhook-signature');
Route::post('webhook/stripe', '\Wave\Http\Controllers\Billing\Webhooks\StripeWebhook@handler');
Route::get('stripe/portal', '\Wave\Http\Controllers\Billing\Stripe@redirect_to_customer_portal')->name('stripe.portal');
Route::redirect('billing', 'settings/subscription')->name('billing');

try {
    if (User::first()) {
        Route::view('/', 'theme::pages.index')->name('home');
        Route::view('/pricing', 'theme::pages.pricing.index')->name('pricing');

        foreach (Page::all() as $page) {
            Route::view($page->slug, 'theme::page', ['page' => $page->toArray()])->name($page->slug);
        }
    }

    if (! User::first()) {
        Route::view('/', 'wave::welcome');
    }
} catch (QueryException $e) {
    //
}
