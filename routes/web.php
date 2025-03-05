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

use Illuminate\Support\Facades\Route;
use Wave\Facades\Wave;
use App\Http\Controllers\GeneratorController;
use App\Http\Controllers\InspirationController;
use App\Http\Controllers\CalendarController;

// Wave routes
Wave::routes();

// Dashboard routes
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth'])->name('dashboard');

// Generator routes
Route::middleware(['auth'])->group(function () {
    Route::get('/generator', [GeneratorController::class, 'index'])->name('generator');
    Route::post('/generator/generate', [GeneratorController::class, 'generate'])->name('generator.generate');
    Route::get('/generator/posts', [GeneratorController::class, 'history'])->name('posts.history');
});

// Inspiration routes
Route::middleware(['auth'])->group(function () {
    Route::get('/inspiration', 'App\Http\Controllers\InspirationController@index')->name('inspiration.index');
    Route::get('/inspiration/interests/manage', 'App\Http\Controllers\InspirationController@interests')->name('inspiration.interests');
    Route::post('/inspiration/interests', 'App\Http\Controllers\InspirationController@updateInterests')->name('inspiration.update_interests');
    Route::get('/inspiration/{id}', 'App\Http\Controllers\InspirationController@show')->name('inspiration.show');
    Route::post('/inspiration/generate', [InspirationController::class, 'generatePost'])->name('inspiration.generate');
});

// Calendar routes
Route::middleware(['auth'])->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
});


