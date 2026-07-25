<?php

return [
    'relying_party_id' => env('PASSKEYS_RELYING_PARTY_ID', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost'),

    'allowed_origins' => array_values(array_filter([
        env('PASSKEYS_ORIGIN', env('APP_URL')),
    ])),

    'user_handle_secret' => env('PASSKEYS_USER_HANDLE_SECRET', config('app.key')),

    'timeout' => (int) env('PASSKEYS_TIMEOUT', 60000),

    'guard' => env('PASSKEYS_GUARD', 'web'),

    'middleware' => ['web'],

    'management_middleware' => ['web', 'auth'],

    'throttle' => 'throttle:6,1',

    'redirect' => env('PASSKEYS_REDIRECT', config('devdojo.auth.settings.redirect_after_auth', '/')),
];
