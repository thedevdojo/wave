<?php

$authMiddleware = config('forum.api.router.auth_middleware', []);

// Categories
Route::group(['prefix' => 'category', 'as' => 'category.'], function () use ($authMiddleware) {
    Route::get('/', ['as' => 'index', 'uses' => 'CategoryController@index']);
    Route::get('{category}', ['as' => 'fetch', 'uses' => 'CategoryController@fetch']);

    Route::group(['middleware' => $authMiddleware], function () {
        Route::post('/', ['as' => 'store', 'uses' => 'CategoryController@store']);
        Route::patch('{category}', ['as' => 'update', 'uses' => 'CategoryController@update']);
        Route::delete('{category}', ['as' => 'delete', 'uses' => 'CategoryController@delete']);
    });

    // Threads by category
    Route::get('{category}/thread', ['as' => 'threads', 'uses' => 'ThreadController@indexByCategory']);
    Route::post('{category}/thread', ['as' => 'threads.store', 'uses' => 'ThreadController@store'])->middleware($authMiddleware);
});

// Threads
Route::group(['prefix' => 'thread', 'as' => 'thread.'], function () use ($authMiddleware) {
    Route::get('recent', ['as' => 'recent', 'uses' => 'ThreadController@recent']);
    Route::get('unread', ['as' => 'unread', 'uses' => 'ThreadController@unread']);
    Route::patch('unread/mark-as-read', ['as' => 'unread.mark-as-read', 'uses' => 'ThreadController@markAsRead'])->middleware($authMiddleware);
    Route::get('{thread}', ['as' => 'fetch', 'uses' => 'ThreadController@fetch']);

    Route::group(['middleware' => $authMiddleware], function () {
        Route::post('{thread}/lock', ['as' => 'lock', 'uses' => 'ThreadController@lock']);
        Route::post('{thread}/unlock', ['as' => 'unlock', 'uses' => 'ThreadController@unlock']);
        Route::post('{thread}/pin', ['as' => 'pin', 'uses' => 'ThreadController@pin']);
        Route::post('{thread}/unpin', ['as' => 'unpin', 'uses' => 'ThreadController@unpin']);
        Route::post('{thread}/rename', ['as' => 'rename', 'uses' => 'ThreadController@rename']);
        Route::post('{thread}/move', ['as' => 'move', 'uses' => 'ThreadController@move']);
        Route::delete('{thread}', ['as' => 'delete', 'uses' => 'ThreadController@delete']);
        Route::post('{thread}/restore', ['as' => 'restore', 'uses' => 'ThreadController@restore']);
    });

    // Posts by thread
    Route::get('{thread}/posts', ['as' => 'posts', 'uses' => 'PostController@indexByThread']);
    Route::post('{thread}/posts', ['as' => 'posts.store', 'uses' => 'PostController@store'])->middleware($authMiddleware);
});

// Posts
Route::group(['prefix' => 'post', 'as' => 'post.'], function () use ($authMiddleware) {
    if (config('forum.api.enable_search')) {
        Route::post('search', ['as' => 'search', 'uses' => 'PostController@search']);
    }

    Route::get('recent', ['as' => 'recent', 'uses' => 'PostController@recent']);
    Route::get('unread', ['as' => 'unread', 'uses' => 'PostController@unread']);
    Route::get('{post}', ['as' => 'fetch', 'uses' => 'PostController@fetch']);
    Route::group(['middleware' => $authMiddleware], function () {
        Route::patch('{post}', ['as' => 'update', 'uses' => 'PostController@update']);
        Route::delete('{post}', ['as' => 'delete', 'uses' => 'PostController@delete']);
        Route::post('{post}/restore', ['as' => 'restore', 'uses' => 'PostController@restore']);
    });
});

// Bulk actions
Route::group(['prefix' => 'bulk', 'as' => 'bulk.', 'namespace' => 'Bulk', 'middleware' => $authMiddleware], function () {
    // Categories
    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::post('manage', ['as' => 'manage', 'uses' => 'CategoryController@manage']);
    });

    // Threads
    Route::group(['prefix' => 'thread', 'as' => 'thread.'], function () {
        Route::post('move', ['as' => 'move', 'uses' => 'ThreadController@move']);
        Route::post('lock', ['as' => 'lock', 'uses' => 'ThreadController@lock']);
        Route::post('unlock', ['as' => 'unlock', 'uses' => 'ThreadController@unlock']);
        Route::post('pin', ['as' => 'pin', 'uses' => 'ThreadController@pin']);
        Route::post('unpin', ['as' => 'unpin', 'uses' => 'ThreadController@unpin']);
        Route::delete('/', ['as' => 'delete', 'uses' => 'ThreadController@delete']);
        Route::post('restore', ['as' => 'restore', 'uses' => 'ThreadController@restore']);
    });

    // Posts
    Route::group(['prefix' => 'post', 'as' => 'post.'], function () {
        Route::delete('/', ['as' => 'delete', 'uses' => 'PostController@delete']);
        Route::post('restore', ['as' => 'restore', 'uses' => 'PostController@restore']);
    });
});
