<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Privacy Settings
    |--------------------------------------------------------------------------
    |
    | These are the default privacy settings applied to new users.
    |
    */

    'defaults' => [
        'profile_visibility' => 'public', // public, private, contacts
        'show_email' => false,
        'show_activity' => true,
        'allow_search_engines' => true,
        'show_online_status' => true,
        'allow_data_collection' => true,
        'allow_personalization' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Visibility Options
    |--------------------------------------------------------------------------
    |
    | Available options for profile visibility settings.
    |
    */

    'visibility_options' => [
        'public' => 'Public - Anyone can view your profile',
        'private' => 'Private - Only you can view your profile',
        'contacts' => 'Contacts Only - Only approved contacts can view',
    ],

];
