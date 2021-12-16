<?php

use Illuminate\Support\Facades\{Auth, Route};
use TCG\Voyager\Facades\Voyager;
use Wave\Facades\Wave;

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

Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Wave::routes();
