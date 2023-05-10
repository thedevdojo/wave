<?php

return [
    'vendor' => env('CASHIER_VENDOR', 'stripe'),

    'stripe' => [
        'calculate_taxes' => env('CASHIER_STRIPE_CALCULATE_TAXES', false),
        'allow_promo_codes' => env('CASHIER_STRIPE_ALLOW_PROMO_CODES', false),
        'mode' => env('STRIPE_MODE', 'test'),
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'live_key' => env('STRIPE_LIVE_KEY'),
        'test_key' => env('STRIPE_TEST_KEY'),
    ],
];
