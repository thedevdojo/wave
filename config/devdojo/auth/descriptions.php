<?php

/*
 * Branding configs for your application
 */
return [
    'settings' => [
        'redirect_after_auth' => 'Where should the user be redirected to after they are authenticated?',
        'registration_enabled' => 'Enable or disable registration functionality. If disabled, users will not be able to register for an account.',
        'registration_show_password_same_screen' => 'During registrations, show the password on the same screen or show it on an individual screen.',
        'registration_include_name_field' => 'During registration, include the Name field.',
        'registration_include_password_confirmation_field' => 'During registration, include the Password Confirmation field.',
        'registration_require_email_verification' => 'During registration, require users to verify their email.',
        'enable_branding' => 'This will toggle on/off the Auth branding at the bottom of each auth screen. Consider leaving on to support and help grow this project.',
        'dev_mode' => 'This is for development mode, when set in Dev Mode Assets will be loaded from Vite',
        'enable_2fa' => 'Enable the ability for users to turn on Two Factor Authentication',
        'enable_email_registration' => 'Enable the ability for users to register via email',
        'login_show_social_providers' => 'Show the social providers login buttons on the login form',
        'center_align_social_provider_button_content' => 'Center align the content in the social provider button?',
        'center_align_text' => 'Center align text?',
        'social_providers_location' => 'The location of the social provider buttons (top or bottom)',
        'check_account_exists_before_login' => 'Determines if the system checks for account existence before login',
    ],
];
