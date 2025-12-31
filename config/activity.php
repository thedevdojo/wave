<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Activity Logging Enabled
    |--------------------------------------------------------------------------
    |
    | Enable or disable activity logging across your application. When disabled,
    | no activity logs will be created.
    |
    */

    'enabled' => env('ACTIVITY_LOG_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Queue Activity Logs
    |--------------------------------------------------------------------------
    |
    | Queue activity log creation to avoid performance impact on user requests.
    | Recommended for high-traffic applications.
    |
    */

    'queue' => env('ACTIVITY_LOG_QUEUE', false),

    /*
    |--------------------------------------------------------------------------
    | Queue Connection
    |--------------------------------------------------------------------------
    |
    | The queue connection to use for queued activity logs.
    |
    */

    'queue_connection' => env('ACTIVITY_LOG_QUEUE_CONNECTION', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Retention Period (Days)
    |--------------------------------------------------------------------------
    |
    | Number of days to keep activity logs. Logs older than this will be
    | automatically deleted. Set to null to keep logs indefinitely.
    |
    */

    'retention_days' => env('ACTIVITY_LOG_RETENTION_DAYS', 90),

];
