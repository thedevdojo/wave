<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Wave\Http\Controllers\AnnouncementController;
use Wave\Http\Controllers\Auth\LoginController;
use Wave\Http\Controllers\BlogController;
use Wave\Http\Controllers\DashboardController;
use Wave\Http\Controllers\HomeController;
use Wave\Http\Controllers\NotificationController;
use Wave\Http\Controllers\PageController;
use Wave\Http\Controllers\ProfileController;
use Wave\Http\Controllers\SettingsController;
use Wave\Http\Controllers\SubscriptionController;

Route::impersonate();

// Documentation routes
Route::view('docs/{page?}', 'docs::index')->where('page', '(.*)');

Route::view('pricing', 'theme::pricing')->name('wave.pricing');

// Billing Routes
Route::post('paddle/webhook', '\Wave\Http\Controllers\WebhookController');

Route::get('p/{page}', [PageController::class, 'page']);

// Additional Auth Routes
Route::get('logout', [LoginController::class, 'logout'])->name('wave.logout');

Route::get('/', [HomeController::class, 'index'])->name('wave.home');

Route::get('@{username}', [ProfileController::class, 'index'])->name('wave.profile');

Route::get('install', function () {
    if (!User::first()->exists()) {
        Artisan::command('db:seed', ['--force' => true]);
        Artisan::call('storage:link');
    }

    return view('wave::install');
})->name('wave.install');

Route::controller(SubscriptionController::class)->group(function () {
    Route::get('test', 'test');
    Route::post('checkout', 'checkout')->name('checkout');
});

Route::group(['middleware' => 'wave'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('wave.dashboard');
});

Route::group(['middleware' => 'admin.user'], function () {
    Route::view('admin/do', 'wave::do');
});

Route::controller(RegisterController::class)
    ->name('wave.')
    ->group(function () {
        Route::get('user/verify/{verification_code}', 'verify')->name('register');
        Route::post('register/complete', 'complete')->name('register-complete');;
    });

Route::controller(BlogController::class)
    ->name('wave.')
    ->prefix('blog')->group(function () {
        Route::get('', 'index')->name('blog');
        Route::get('{category}', 'category')->name('blog.category');
        Route::get('{category}/{post}', 'post')->name('blog.post');
    });

Route::name('wave.')
    ->middleware(['auth'])
    ->group(function () {
        Route::view('checkout/welcome', 'theme::welcome');
        Route::view('trial_over', 'theme::trial_over')->name('trial_over');
        Route::view('cancelled', 'theme::cancelled')->name('cancelled');

        Route::controller(SettingsController::class)
            ->prefix('settings')
            ->group(function () {
                Route::get('{section?}', 'index')->name('settings');
                Route::post('profile', 'profilePut')->name('settings.profile.put');
                Route::put('security', 'securityPut')->name('settings.security.put');
                Route::post('api', 'apiPost')->name('settings.api.post');
                Route::put('api/{id?}', 'apiPut')->name('settings.api.put');
                Route::delete('api/{id?}', 'apiDelete')->name('settings.api.delete');
                Route::get('invoices/{invoice}', 'invoice')->name('invoice');
            });

        Route::controller(AnnouncementController::class)
            ->prefix('announcements')
            ->group(function () {
                Route::get('', 'index')->name('announcements');
                Route::get('{id}', 'announcement')->name('announcement');
                Route::post('read', 'read')->name('announcements.read');
            });

        Route::controller(NotificationController::class)
            ->prefix('notifications')
            ->group(function () {
                Route::get('', 'index')->name('notifications');
                Route::post('read/{id}', 'delete')->name('notification.read');
            });

        Route::controller(SubscriptionController::class)
            ->group(function () {
                Route::post('subscribe', 'subscribe')->name('subscribe');
                Route::post('switch-plans', 'switchPlans')->name('switch-plans');
                Route::post('cancel', 'cancel')->name('cancel');
            });
    });
