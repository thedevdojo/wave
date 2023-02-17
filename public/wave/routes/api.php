<?php

use Illuminate\Support\Facades\Route;

Route::post('login', '\Wave\Http\Controllers\API\AuthController@login');
Route::post('register', '\Wave\Http\Controllers\API\AuthController@register');
Route::post('logout', '\Wave\Http\Controllers\API\AuthController@logout');
Route::post('refresh', '\Wave\Http\Controllers\API\AuthController@refresh');
Route::post('token', '\Wave\Http\Controllers\API\AuthController@token');

// BROWSE
Route::get('/{datatype}', '\Wave\Http\Controllers\API\ApiController@browse');

// READ
Route::get('/{datatype}/{id}', '\Wave\Http\Controllers\API\ApiController@read');

// EDIT
Route::put('/{datatype}/{id}', '\Wave\Http\Controllers\API\ApiController@edit');

// ADD
Route::post('/{datatype}', '\Wave\Http\Controllers\API\ApiController@add');

// DELETE
Route::delete('/{datatype}/{id}', '\Wave\Http\Controllers\API\ApiController@delete');
