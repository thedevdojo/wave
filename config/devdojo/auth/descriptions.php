<?php

/*
 * Branding configs for your application
 */
return [
    'settings' => [
        'redirect_after_auth' => 'Where should the user be redirected to after they are authenticated?',
        'registration_show_password_same_screen' => 'During registrations, show the password on the same screen or show it on an individual screen.',
        'registration_include_name_field' => 'During registration, include the Name field.',
        'registration_require_email_verification' => 'During registration, require users to verify their email.',
        'enable_branding' => 'This will toggle on/off the Auth branding at the bottom of each auth screen. Consider leaving on to support and help grow this project.',
        'dev_mode' => 'This is for development mode, when set in Dev Mode Assets will be loaded from Vite',
        'enable_2fa' => 'Enable the ability for users to turn on Two Factor Authentication',
        'login_show_social_providers' => 'Show the social providers login buttons on the login form',
        'social_providers_location' => 'The location of the social provider buttons (top or bottom)',
    ],
];
