<?php

use App\Models\User;

return [

    // Lifetime in minutes of JWTs issued by the API. auth_token_expires applies
    // to /api/login, /api/register and /api/refresh; key_token_expires to /api/token.
    'api' => [
        'auth_token_expires' => 60,
        'key_token_expires' => 60,
    ],

    'auth' => [
        'min_password_length' => 8,
        'login_max_attempts' => 5, // Failed passwords per normalized email and IP.
        'login_ip_max_attempts' => 50, // Failed passwords across accounts from one IP.
        'login_decay_seconds' => 60,
    ],

    'primary_color' => '#000000',

    'user_model' => User::class,
    'show_docs' => env('WAVE_DOCS', true),
    'demo' => env('WAVE_DEMO', false),
    'dev_bar' => env('WAVE_BAR', false),
    'default_user_role' => 'registered',

    // Folder that plugins are loaded from and created in.
    'plugins_path' => resource_path('plugins'),

    'billing_provider' => env('BILLING_PROVIDER', 'stripe'),

    'paddle' => [
        'vendor' => env('PADDLE_VENDOR_ID', ''),
        'api_key' => env('PADDLE_API_KEY', ''),
        'env' => env('PADDLE_ENV', 'sandbox'),
        'public_key' => env('PADDLE_PUBLIC_KEY', ''),
        'webhook_secret' => env('PADDLE_WEBHOOK_SECRET', ''),
    ],

    'stripe' => [
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

];
