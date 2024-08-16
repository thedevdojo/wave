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

use Illuminate\Support\Facades\Storage;

Route::get('prostuff', function () {
    $user = auth()->user();
    $user->setKeyValue('cool', 'beans');
    //dd($user->keyValues);

    // $keyValuesQuery = $user->keyValues();
    // dd($keyValuesQuery->toSql(), $keyValuesQuery->getBindings());
});


Route::get('add_role', function(){
    auth()->user()->assignRole('registered');
});

Route::get('get_role', function(){
    $plan = \Wave\Plan::first();
    dd($plan->role->name);
    auth()->user()->assignRole('registered');
});

Route::get('switchPlans', function(){
    $subscription = \Wave\Subscription::find(30);
    $subscription->user->switchPlans('basic');
});
