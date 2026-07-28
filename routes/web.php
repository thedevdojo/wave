<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Wave\Facades\Wave;
use App\Http\Controllers\NylasController;
use App\Http\Controllers\NylasWebhookController;

// Webhook endpoint for Nylas (without CSRF, and open to the internet)
Route::match(['get', 'post'], 'webhooks/nylas', [NylasWebhookController::class, 'handle'])
    ->name('webhooks.nylas');

// Wave routes
Wave::routes();

Route::middleware('auth')->group(function () {
    Route::get('nylas/connect', [NylasController::class, 'connect'])->name('nylas.connect');
    Route::get('nylas/callback', [NylasController::class, 'callback'])->name('nylas.callback');
    Route::delete('nylas/disconnect/{id}', [NylasController::class, 'disconnect'])->name('nylas.disconnect');
});
