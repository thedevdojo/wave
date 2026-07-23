<?php

/*
 * These are some default authentication settings
 */
return [
    'redirect_after_auth' => '/dashboard',
    'redirect_after_logout' => '/',
    'registration_enabled' => true,
    'registration_show_password_same_screen' => true,
    'registration_include_name_field' => false,
    'registration_include_password_confirmation_field' => false,
    'registration_require_email_verification' => false,
    'password_min_length' => 8,
    'password_require_uppercase' => false,
    'password_require_numeric' => false,
    'password_require_special_character' => false,
    'password_require_uncompromised' => false,
    'password_show_requirements' => true,
    'enable_branding' => false,
    'dev_mode' => false,
    'enable_2fa' => true, // Enable or disable 2FA functionality globally
    'enable_passkeys' => true, // Enable passkey authentication on login screens
    'enable_email_registration' => true,
    'login_show_social_providers' => true,
    'center_align_social_provider_button_content' => true,
    'center_align_text' => true,
    'social_providers_location' => 'bottom',
    'check_account_exists_before_login' => false,
    'include_wire_navigate' => true,
    // Code-based email verification (the inline companion to the signed link).
    'verification_code_expires_in' => '15',      // minutes
    'verification_code_max_attempts' => 5,     // wrong guesses before a resend is forced
    'verification_code_resend_cooldown' => 60, // seconds between sends
];
