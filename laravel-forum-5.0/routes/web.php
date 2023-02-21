<?php

$authMiddleware = config('forum.web.router.auth_middleware');
$prefix = config('forum.web.route_prefixes');

// Standalone routes
Route::get('/', ['as' => 'index', 'uses' => 'CategoryController@index']);

Route::get('recent', ['as' => 'recent', 'uses' => 'ThreadController@recent']);

Route::get('unread', ['as' => 'unread', 'uses' => 'ThreadController@unread']);
Route::patch('unread/mark-as-read', ['as' => 'unread.mark-as-read', 'uses' => 'ThreadController@markAsRead'])->middleware($authMiddleware);

Route::get('manage', ['as' => 'category.manage', 'uses' => 'CategoryController@manage'])->middleware($authMiddleware);

// Categories
Route::post($prefix['category'].'/create', ['as' => 'category.store', 'uses' => 'CategoryController@store']);
Route::group(['prefix' => $prefix['category'].'/{category}-{category_slug}'], function () use ($prefix, $authMiddleware) {
    Route::get('/', ['as' => 'category.show', 'uses' => 'CategoryController@show']);
    Route::patch('/', ['as' => 'category.update', 'uses' => 'CategoryController@update'])->middleware($authMiddleware);
    Route::delete('/', ['as' => 'category.delete', 'uses' => 'CategoryController@delete'])->middleware($authMiddleware);

    Route::get($prefix['thread'].'/create', ['as' => 'thread.create', 'uses' => 'ThreadController@create']);
    Route::post($prefix['thread'].'/create', ['as' => 'thread.store', 'uses' => 'ThreadController@store'])->middleware($authMiddleware);
});

// Threads
Route::group(['prefix' => $prefix['thread'].'/{thread}-{thread_slug}'], function () use ($prefix, $authMiddleware) {
    Route::get('/', ['as' => 'thread.show', 'uses' => 'ThreadController@show']);
    Route::get($prefix['post'].'/{post}', ['as' => 'post.show', 'uses' => 'PostController@show']);

    Route::group(['middleware' => $authMiddleware], function () use ($prefix) {
        Route::patch('/', ['as' => 'thread.update', 'uses' => 'ThreadController@update']);
        Route::post('lock', ['as' => 'thread.lock', 'uses' => 'ThreadController@lock']);
        Route::post('unlock', ['as' => 'thread.unlock', 'uses' => 'ThreadController@unlock']);
        Route::post('pin', ['as' => 'thread.pin', 'uses' => 'ThreadController@pin']);
        Route::post('unpin', ['as' => 'thread.unpin', 'uses' => 'ThreadController@unpin']);
        Route::post('move', ['as' => 'thread.move', 'uses' => 'ThreadController@move']);
        Route::post('restore', ['as' => 'thread.restore', 'uses' => 'ThreadController@restore']);
        Route::post('rename', ['as' => 'thread.rename', 'uses' => 'ThreadController@rename']);
        Route::delete('/', ['as' => 'thread.delete', 'uses' => 'ThreadController@delete']);

        Route::get('reply', ['as' => 'post.create', 'uses' => 'PostController@create']);
        Route::post('reply', ['as' => 'post.store', 'uses' => 'PostController@store']);
        Route::get($prefix['post'].'/{post}/edit', ['as' => 'post.edit', 'uses' => 'PostController@edit']);
        Route::patch($prefix['post'].'/{post}', ['as' => 'post.update', 'uses' => 'PostController@update']);
        Route::get($prefix['post'].'/{post}/delete', ['as' => 'post.confirm-delete', 'uses' => 'PostController@confirmDelete']);
        Route::get($prefix['post'].'/{post}/restore', ['as' => 'post.confirm-restore', 'uses' => 'PostController@confirmRestore']);
        Route::delete($prefix['post'].'/{post}', ['as' => 'post.delete', 'uses' => 'PostController@delete']);
        Route::post($prefix['post'].'/{post}/restore', ['as' => 'post.restore', 'uses' => 'PostController@restore']);
    });
});

// Bulk actions
Route::group(['prefix' => 'bulk', 'as' => 'bulk.', 'namespace' => 'Bulk', 'middleware' => $authMiddleware], function () {
    // Categories
    Route::post('category/manage', ['as' => 'category.manage', 'uses' => 'CategoryController@manage']);

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
