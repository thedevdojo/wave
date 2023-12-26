# Routing

In this section we'll quickly cover the Wave routes.

- [Wave Web Routes](#web-routes)
- [Wave API Routes](#api-routes)
- [Wave Middleware](#wave-middleware)

---

<a name="web-routes"></a>
### Wave Web Routes

If you take a look inside of `wave/routes/web.php` you will be able to see all Wave web routes.

Next, if you take a look inside your `routes/web.php`, you will see the following line:

```php
// Include Wave Routes
Wave::routes();
```

This line includes all the Wave routes into your application.

<a name="api-routes"></a>
### Wave API Routes

The Wave API routes are located at `wave/routes/api.php`. The contents of the file are as follows:

```php
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
```

Then, if you take a look inside of your `routes/api.php`, you will see the following line:

```php
// Include Wave Routes
Wave::api();
```

This line includes all the Wave API routes into your application API.

<a name="wave-middleware"></a>
### Wave Middleware

Inside of the Wave routes.php file you will see the following line:

```php
Route::group(['middleware' => 'wave'], function () {
    Route::get('dashboard', '\Wave\Http\Controllers\DashboardController@index')->name('wave.dashboard');
});
```

This is the only current route protected by the `wave` middleware. The `wave` middleware is used to protect routes against users who no longer have an active subscription or are no longer on a trial. You can include your application routes inside of this middleware:

```php
Route::group(['middleware' => 'wave'], function () {
    // Add your application routes here.
});
```

You may also wish to include this middleware in a single route:

```php
Route::get('awesome', 'AwesomeController@index')->middleware('wave');
```

And now your application routes will be protected from users who are no longer active paying users.
