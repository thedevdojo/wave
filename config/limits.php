<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Feature Limits
    |--------------------------------------------------------------------------
    |
    | Define countable features and their associated models. Each feature
    | can have a limit defined per plan. The model and column are used to
    | count the user's current usage.
    |
    | Example:
    |   'projects' => [
    |       'model' => App\Models\Project::class,
    |       'column' => 'user_id',
    |   ],
    |
    */

    'features' => [
        'api_keys' => [
            'model' => \Wave\ApiKey::class,
            'column' => 'user_id',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Bypass
    |--------------------------------------------------------------------------
    |
    | When enabled, users with the admin role bypass all feature limits.
    |
    */

    'admin_bypass' => true,

    /*
    |--------------------------------------------------------------------------
    | Default Limits
    |--------------------------------------------------------------------------
    |
    | Default limits for users without an active subscription. Set to null
    | for unlimited, 0 to disable the feature entirely.
    |
    */

    'defaults' => [
        'api_keys' => 1,
    ],

];
