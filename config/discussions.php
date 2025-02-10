<?php

return [
    'headline_logo' => '/vendor/foundationapp/discussions/assets/images/logo-light.png',

    'user' => [
        'namespace' => 'App\Models\User',
        'database_field_with_user_name' => 'name',
        'relative_url_to_profile' => '',
        'relative_url_to_image_assets' => '',
        'avatar_image_database_field' => '',
    ],

    'load_more' => [
        'posts' => 10,
        'discussions' => 10,
    ],

    'home_route' => 'discussions',
    'route_prefix' => 'discussions',
    'route_prefix_post' => 'discussion',

    /*
    |--------------------------------------------------------------------------
    | A Few security measures to prevent spam on your forum
    |--------------------------------------------------------------------------
    |
    | Here are a few configurations that you can add to your forum to prevent
    | possible spammers or bots.
    |
    |   *limit_time_between_posts*: Stop user from being able to spam by making
    |       them wait a specified time before being able to post again.
    |
    |   *time_between_posts*: In minutes, the time a user must wait before
    |       being allowed to add more content. Only valid if above value is
    |       set to true.
    |
    */

    'security' => [
        'limit_time_between_posts' => true, // true or false
        'time_between_posts' => 1, // In minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Styles for discussions
    |--------------------------------------------------------------------------
    |
    | This is a minimal config to update a few of the sytles in the discussions
    |
    */

    'styles' => [
        'rounded' => 'rounded-lg',
        'sidebar_width' => 'w-56',
        'container_classes' => 'max-w-7xl md:px-12 xl:px-20 mx-auto py-12',
        'container_max_width' => 'max-w-[1120px]',
        'header_classes' => 'dark:text-white text-gray-900 text-4xl font-semibold tracking-tighter',
    ],

    /*
    |--------------------------------------------------------------------------
    | Editor
    |--------------------------------------------------------------------------
    |
    | You may wish to choose between a couple different editors. At the moment
    | The following editors are supported:
    |   - textarea
    /   - richeditor
    |   - markdown
    */

    'editor' => 'richeditor',

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    |
    | Here you will define the categories that are available for discussions.
    | If you do not wish to include categories in your discussions, you can
    | set the 'show_categories' value to false.
    |
    */

    'show_categories' => true,

    'categories' => [
        'announcements' => [
            'icon' => '📣',
            'title' => 'Announcements',
            'description' => 'Important announcements from the administrators.',
        ],
        'general' => [
            'icon' => '💬',
            'title' => 'General Discussion',
            'description' => 'Chat about anything and everything here',
        ],
        'ideas' => [
            'icon' => '💡',
            'title' => 'Ideas',
            'description' => 'Share ideas for new features',
        ],
        'qa' => [
            'icon' => '🙏',
            'title' => 'Q&A',
            'description' => 'Ask the community for help',
        ],
        'show-and-tell' => [
            'icon' => '🙌',
            'title' => 'Show and tell',
            'description' => 'Show off something you\'ve made',
        ],
    ],
];
