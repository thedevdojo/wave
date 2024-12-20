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

// Wave routes
Wave::routes();

// Restriction for DevDojo Auth Setup - Restrict /auth/setup/* to admin users only
Route::group(['prefix' => 'auth/setup', 'middleware' => ['auth', 'admin']], function () {
    Route::any('{any}', function () {
        return view('wave::auth.setup'); // Replace with the appropriate view or logic
    })->where('any', '.*'); // Wildcard to match anything after /auth/setup/
});
