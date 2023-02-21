<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Old thread threshold
    |--------------------------------------------------------------------------
    |
    | The minimum age of a thread before it should be considered old. This
    | determines whether or not a thread can be considered new or unread for
    | any user. Increasing this value to cover a longer period will increase
    | the ultimate size of your forum_threads_read table. Must be a valid
    | strtotime() string, or set to false to completely disable age-sensitive
    | thread features.
    |
    */

    'old_thread_threshold' => '7 days',

    /*
    |--------------------------------------------------------------------------
    | Soft deletes
    |--------------------------------------------------------------------------
    |
    | Disable this if you want threads and posts to be permanently removed from
    | your database when they're deleted. Note that by default, the option
    | to hard delete threads and posts exists regardless of this setting.
    |
    */

    'soft_deletes' => true,

    /*
    |--------------------------------------------------------------------------
    | Display trashed (soft-deleted) posts
    |--------------------------------------------------------------------------
    |
    | Enable this if you want to display placeholder messages for soft-deleted
    | posts instead of hiding them altogether. Enabling this will override the
    | viewTrashedPosts ability.
    |
    */

    'display_trashed_posts' => true,

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | "Per page" values for each model. These are applied for both the web and
    | API routes.
    |
    */

    'pagination' => [
        'threads' => 20,
        'posts' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    |
    | Values for some customisable validation rules.
    |
    */

    'validation' => [
        'title_min' => 3,
        'content_min' => 3,
    ],

];
