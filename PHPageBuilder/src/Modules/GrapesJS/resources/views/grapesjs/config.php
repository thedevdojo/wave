<?php

return [
    'container' => '#gjs',
    'noticeOnUnload' => false,
    'avoidInlineStyle' => true,
    'allowScripts' => true,
    'storageManager' => [
        'type' => 'local',
        'autoload' => false,
        'autosave' => false
    ],
    'canvasCss' => 'body {height: auto;}',  // prevent scrollbar jump on pasting in CKEditor
    'assetManager' => [
        'upload' => phpb_url('pagebuilder', ['action' => 'upload', 'page' => $page->getId()]),
        'uploadName' => 'files',
        'multiUpload' => false,
        'assets' => $assets
    ],
    'styleManager' => [
        'sectors' => [[
            'id' => 'position',
            'name' => phpb_trans('pagebuilder.style-manager.sectors.position'),
            'open' => true,
            'buildProps' => ['width', 'height', 'min-width', 'min-height', 'max-width', 'max-height', 'padding', 'margin', 'text-align'],
            'properties' => [[
                'property' => 'text-align',
                'list' => [
                    ['value' => 'left', 'className' => 'fa fa-align-left'],
                    ['value' => 'center', 'className' => 'fa fa-align-center'],
                    ['value' => 'right', 'className' => 'fa fa-align-right'],
                    ['value' => 'justify', 'className' => 'fa fa-align-justify'],
                ],
            ]]
        ], [
            'id' => 'background',
            'name' => phpb_trans('pagebuilder.style-manager.sectors.background'),
            'open' => false,
            'buildProps' => ['background-color', 'background']
        ]]
    ],
    'selectorManager' => [
        'label' => phpb_trans('pagebuilder.selector-manager.label'),
        'statesLabel' => phpb_trans('pagebuilder.selector-manager.states-label'),
        'selectedLabel' => phpb_trans('pagebuilder.selector-manager.selected-label'),
        'states' => [
            ['name' => 'hover', 'label' => phpb_trans('pagebuilder.selector-manager.state-hover')],
            ['name' => 'active', 'label' => phpb_trans('pagebuilder.selector-manager.state-active')],
            ['name' => 'nth-of-type(2n)', 'label' => phpb_trans('pagebuilder.selector-manager.state-nth')]
        ],
    ],
    'traitManager' => [
        'labelPlhText' => '',
        'labelPlhHref' => 'https://website.com'
    ],
    'panels' => [
        'defaults' => [
            [
                'id' => 'views',
                'buttons' => [
                    [
                        'id' => 'open-blocks-button',
                        'className' => 'fa fa-th-large',
                        'command' => 'open-blocks',
                        'togglable' => 0,
                        'attributes' => ['title' => phpb_trans('pagebuilder.view-blocks')],
                        'active' => true,
                    ],
                    [
                        'id' => 'open-settings-button',
                        'className' => 'fa fa-cog',
                        'command' => 'open-tm',
                        'togglable' => 0,
                        'attributes' => ['title' => phpb_trans('pagebuilder.view-settings')],
                    ],
                    [
                        'id' => 'open-style-button',
                        'className' => 'fa fa-paint-brush',
                        'command' => 'open-sm',
                        'togglable' => 0,
                        'attributes' => ['title' => phpb_trans('pagebuilder.view-style-manager')],
                    ]
                ]
            ],
        ]
    ],
    'canvas' => [
        'styles' => [
            phpb_asset('pagebuilder/page-injection.css'),
        ],
    ],
    'plugins' => ['grapesjs-touch', 'gjs-plugin-ckeditor'],
    'pluginsOpts' => [
        'gjs-plugin-ckeditor' => [
            'position' => 'left',
            'options' => [
                'startupFocus' => true,
                'allowedContent' => true,
                //'extraAllowedContent' => '*(*);*[*];ul ol li span', // allows classes, inline styles and certain elements
                'enterMode' => 'CKEDITOR.ENTER_BR',
                'extraPlugins' => 'sharedspace,justify,colorbutton,font',
                'toolbar' => [
                    ['Bold', 'Italic', 'Underline', 'Strike', 'Undo', 'Redo'],
                    [
                        'name' => 'links',
                        'items' => ['Link', 'Unlink']
                    ],
                    [
                        'name' => 'styles',
                        'items' => ['FontSize']
                    ],
                    [
                        'name' => 'paragraph',
                        'groups' => ['list', 'indent', 'blocks', 'align'],
                        'items' => ['NumberedList', 'BulletedList', 'Blockquote', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-']
                    ],
                    [
                        'name' => 'colors',
                        'items' => ['TextColor', 'BGColor']
                    ],
                ],
            ]
        ]
    ]
];
