<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'nylas' => [
        'client_id' => env('NYLAS_CLIENT_ID'),
        'client_secret' => env('NYLAS_API_KEY'),
        'api_uri' => env('NYLAS_API_URI', 'https://api.us.nylas.com'),
    ],

];
