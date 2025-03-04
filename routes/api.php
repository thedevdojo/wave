<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneratorController;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return auth()->user();
});

Wave::api();

// Posts Example API Route
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/posts', [ApiController::class, 'posts']);
});

// Browser Extension API Routes
Route::prefix('extension')->group(function () {
    // Authentication routes
    Route::post('/login', [ApiController::class, 'login']);
    Route::post('/logout', [ApiController::class, 'logout'])->middleware('auth:api');
    
    // User info and credits
    Route::get('/user', [ApiController::class, 'getUser'])->middleware('auth:api');
    Route::get('/credits', [ApiController::class, 'getCredits'])->middleware('auth:api');
    
    // Post generation
    Route::post('/generate', [GeneratorController::class, 'generate'])->middleware('auth:api');
    
    // Post history
    Route::get('/history', [ApiController::class, 'getPostHistory'])->middleware('auth:api');
    Route::post('/save', [ApiController::class, 'savePost'])->middleware('auth:api');
});

// Inspiration routes
Route::middleware('auth:api')->group(function () {
    Route::get('/inspirations', 'App\Http\Controllers\Api\InspirationController@index');
    Route::get('/inspirations/{id}', 'App\Http\Controllers\Api\InspirationController@show');
    Route::get('/inspiration-categories', 'App\Http\Controllers\Api\InspirationController@categories');
    Route::get('/inspiration-tags', 'App\Http\Controllers\Api\InspirationController@tags');
    Route::post('/user-interests', 'App\Http\Controllers\Api\InspirationController@updateInterests')->name('api.user-interests');
});
