<?php

return [

    'mailers' => [
        'mailgun' => [
            'transport' => 'mailgun',
        ],
    ],

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
