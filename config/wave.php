<?php

return [

	'profile_fields' => [
		'about'
	],

	'api' => [
		'auth_token_expires' 	=> 60,
		'key_token_expires'		=> 1,
	],

	'auth' => [
		'min_password_length' => 5,
	],

	'user_model' => \App\Models\User::class,
	'show_docs' => env('WAVE_DOCS', true),
    'demo' => env('WAVE_DEMO', false),
    'dev_bar' => env('WAVE_BAR', false),

    'paddle' => [
        'vendor' => env('PADDLE_VENDOR_ID', ''),
        'auth_code' => env('PADDLE_API_KEY', ''),
		'api_key' => env('PADDLE_API_KEY', ''),
		'client_side_token' => env('PADDLE_CLIENT_SIDE_TOKEN', ''),
        'env' => env('PADDLE_ENV', 'sandbox'),
        'public_key' => env('PADDLE_PUBLIC_KEY', ''),
    ]

];
