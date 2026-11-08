<?php

return [

    'mailers' => [
        'mailgun' => [
            'transport' => 'mailgun',
        ],

        'mailtrap-sdk' => [
            'transport' => 'mailtrap-sdk',
        ],
    ],

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
