<?php

$pages = [

    'welcome' => 'welcome.md',
    'installation' => 'installation.md',
    'configurations' => 'configurations.md',
    'upgrading' => 'upgrading.md',

    'features/authentication' => 'features/authentication.md',
    'features/user-profiles' => 'features/user-profiles.md',
    'features/user-impersonation' => 'features/user-impersonation.md',
    'features/billing' => 'features/billing.md',
    'features/subscription-plans' => 'features/subscription-plans.md',
    'features/user-roles' => 'features/user-roles.md',
    'features/notifications' => 'features/notifications.md',
    'features/announcements' => 'features/announcements.md',
    'features/blog' => 'features/blog.md',
    'features/api' => 'features/api.md',
    'features/admin' => 'features/admin.md',
    'features/themes' => 'features/themes.md',

    'concepts/routing' => 'concepts/routing.md',
    'concepts/themes' => 'concepts/themes.md',
    'concepts/structure' => 'concepts/structure.md',


];

$menu_items = [

    (object)[
        'title' => 'Getting Started',
        'sections' => (object)[
            (object)[
                'title' => 'Welcome',
                'url' => '/docs'
            ],
            (object)[
                'title' => 'Installation',
                'url' => '/docs/installation'
            ],
            (object)[
                'title' => 'Configurations',
                'url' => '/docs/configurations'
            ],
            (object)[
                'title' => 'Upgrading',
                'url' => '/docs/upgrading'
            ]
        ]
    ],
    (object)[
        'title' => 'Features',
        'sections' => (object)[
            (object)[
                'title' => 'Authentication',
                'url' => '/docs/features/authentication'
            ],
            (object)[
                'title' => 'User Profiles',
                'url' => '/docs/features/user-profiles'
            ],
            (object)[
                'title' => 'User Impersonation',
                'url' => '/docs/features/user-impersonation'
            ],
            (object)[
                'title' => 'Billing',
                'url' => '/docs/features/billing'
            ],
            (object)[
                'title' => 'Subscription Plans',
                'url' => '/docs/features/subscription-plans'
            ],
            (object)[
                'title' => 'User Roles',
                'url' => '/docs/features/user-roles'
            ],
            (object)[
                'title' => 'Notifications',
                'url' => '/docs/features/notifications'
            ],
            (object)[
                'title' => 'Announcements',
                'url' => '/docs/features/announcements'
            ],
            (object)[
                'title' => 'Blog',
                'url' => '/docs/features/blog'
            ],
            (object)[
                'title' => 'API',
                'url' => '/docs/features/api'
            ],
            (object)[
                'title' => 'Admin',
                'url' => '/docs/features/admin'
            ],
            (object)[
                'title' => 'Themes',
                'url' => '/docs/features/themes'
            ]
        ]
    ],

    (object)[
        'title' => 'Basic Concepts',
        'sections' => (object)[
            (object)[
                'title' => 'Routing',
                'url' => '/docs/concepts/routing'
            ],
            (object)[
                'title' => 'Themes',
                'url' => '/docs/concepts/themes'
            ],
            (object)[
                'title' => 'Structure',
                'url' => '/docs/concepts/structure'
            ]
        ]
    ],

    (object)[
        'title' => 'Resources',
        'sections' => (object)[
            (object)[
                'title' => 'Videos',
                'url' => 'https://devdojo.com/course/wave',
                'attributes' => 'target="_blank"'
            ],
            (object)[
                'title' => 'Support',
                'url' => 'https://devdojo.com/wave#pro',
                'attributes' => 'target="_blank"'
            ],
            (object)[
                'title' => 'Laravel',
                'url' => 'https://laravel.com',
                'attributes' => 'target="_blank"'
            ],
            (object)[
                'title' => 'Voyager',
                'url' => 'https://voyager.devdojo.com',
                'attributes' => 'target="_blank"'
            ],
            (object)[
                'title' => 'DigitalOcean',
                'url' => 'https://digitalocean.com',
                'attributes' => 'target="_blank"'
            ]
        ]
    ],

];


$uri = trim(str_replace('/docs', '', Request::getRequestUri()), '/');

// Get the requested page and check if we are at home.
$home = false;
if($uri == "")
{
    $page = 'welcome.md';
    $home = true;
}
else
{
    if( !isset( $pages[$uri] ) ){
        abort(404);
    }
    $page = $pages[$uri];
}

$title = 'Welcome to Wave';

foreach($menu_items as $item){
    foreach($item->sections as $index => $section){
        if(Request::getRequestUri() && Request::getRequestUri() == $section->url){
            $title = $section->title . ' - Wave SAAS Starter Kit';
        }
    }
}

$file = file_get_contents(  base_path() . '/wave/docs/' . $page );

?>
