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

Route::controller(RegisterController::class)->group(function () {
    Route::get('user/verify/{verification_code}', 'verify')->name('wave.register');
    Route::post('register/complete', 'complete')->name('wave.register-complete');;
});

Route::controller(BlogController::class)->prefix('blog')->group(function () {
    Route::get('', 'index')->name('wave.blog');
    Route::get('{category}', 'category')->name('wave.blog.category');
    Route::get('{category}/{post}', 'post')->name('wave.blog.post');
});

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

Route::group(['middleware' => 'auth'], function () {

    Route::view('checkout/welcome', 'theme::welcome');
    Route::view('trial_over', 'theme::trial_over')->name('wave.trial_over');
    Route::view('cancelled', 'theme::cancelled')->name('wave.cancelled');

    Route::controller(SettingsController::class)
        ->prefix('settings')
        ->group(function () {
            Route::get('{section?}', 'index')->name('wave.settings');
            Route::post('profile', 'profilePut')->name('wave.settings.profile.put');
            Route::put('security', 'securityPut')->name('wave.settings.security.put');
            Route::post('api', 'apiPost')->name('wave.settings.api.post');
            Route::put('api/{id?}', 'apiPut')->name('wave.settings.api.put');
            Route::delete('api/{id?}', 'apiDelete')->name('wave.settings.api.delete');
            Route::get('invoices/{invoice}', 'invoice')->name('wave.invoice');
        });

    Route::controller(AnnouncementController::class)
        ->prefix('announcements')
        ->group(function () {
            Route::get('', 'index')->name('wave.announcements');
            Route::get('{id}', 'announcement')->name('wave.announcement');
            Route::post('read', 'read')->name('wave.announcements.read');
        });

    Route::controller(NotificationController::class)
        ->prefix('notifications')
        ->group(function () {
            Route::get('', 'index')->name('wave.notifications');
            Route::post('read/{id}', 'delete')->name('wave.notification.read');
        });

    Route::controller(SubscriptionController::class)
        ->group(function () {
            Route::post('subscribe', 'subscribe')->name('wave.subscribe');
            Route::post('switch-plans', 'switchPlans')->name('wave.switch-plans');
            Route::post('cancel', 'cancel')->name('wave.cancel');
        });
});
