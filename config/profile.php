<?php

return [
	'fields' => [
		'about' => [
			'label' => 'About',
			'type' => 'Textarea',
			'rules' => 'required'
        ],
        'favorite_color' => [
            'label' => 'Favorite Color',
			'type' => 'TextInput',
			'rules' => 'required'
        ],
        'more_about' => [
            'label' => 'More About',
            'type' => 'RichEditor',
            'rules' => 'required'
        ],
        'background_image' => [
            'label' => 'BG image',
            'type' => 'FileUpload',
            'rules' => 'required'
        ],
        'skills' => [
            'label' => 'Skills',
            'type' => 'CheckboxList',
            'rules' => 'required',
            'options' => [
                'tailwind' => 'Tailwind CSS',
                'alpine' => 'Alpine.js',
                'laravel' => 'Laravel',
                'livewire' => 'Laravel Livewire',
            ]
        ],
        'status' => [
            'label' => 'Status?',
            'type' => 'Radio',
            'rules' => 'required',
            'options' => [
                'draft' => 'Draft',
                'scheduled' => 'Scheduled',
                'published' => 'Published'
            ]
        ],
        'post' => [
            'label' => 'Write a Post',
            'type' => 'MarkdownEditor',
            'rules' => 'required'
        ],
	],
];
