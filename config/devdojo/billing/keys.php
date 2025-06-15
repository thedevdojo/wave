<?php

return [
    'stripe' => [
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'paddle' => [
        'vendor_id' => env('PADDLE_VENDOR_ID'),
        'api_key' => env('PADDLE_API_KEY'),
        'env' => env('PADDLE_ENV'),
        'public_key' => env('PADDLE_PUBLIC_KEY'),
    ],
];
