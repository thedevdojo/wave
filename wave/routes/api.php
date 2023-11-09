<?php

use Illuminate\Support\Facades\Route;
use Wave\Http\Controllers\API\ApiController;
use Wave\Http\Controllers\API\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('token', 'token');
});

Route::controller(ApiController::class)->group(function () {
    Route::get('{datatype}', 'browse');
    Route::get('{datatype}/{id}', 'read');
    Route::put('{datatype}/{id}', 'edit');
    Route::post('{datatype}', 'add');
    Route::delete('{datatype}/{id}', 'delete');
});
