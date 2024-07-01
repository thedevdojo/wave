<?php

/*
 * Branding configs for your application
 */

return [
    'logo' => [
        'type' => 'svg',
        'image_src' => '',
        'svg_string' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" class="w-full h-full" fill="none"><path fill="#006EFF" d="M28.95 172.841h36.147a4 4 0 0 0 4-4v-36.146a4 4 0 0 0-4-4l-36.147-.001a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4ZM81.926 172.843l36.147.001a4 4 0 0 0 4-4v-36.147a4 4 0 0 0-4-4H81.926a4 4 0 0 0-4 4v36.146a4 4 0 0 0 4 4Z"/><path fill="#009AFF" d="M81.927 122.073h36.146c2.21 0 4-1.79 4-4V81.927a4 4 0 0 0-4-4H81.927a4 4 0 0 0-4 4v36.146a4 4 0 0 0 4 4Z"/><path fill="#00CEFF" d="M134.902 122.073h36.147a4 4 0 0 0 4-4V81.927a4 4 0 0 0-4-4h-36.147a4 4 0 0 0-4 4v36.146a4 4 0 0 0 4 4ZM134.903 71.303h36.147a4 4 0 0 0 4-4V31.156a4 4 0 0 0-4-4h-36.147a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4Z"/></svg>',
        'height' => '32',
    ],
    'background' => [
        'color' => '#ffffff',
        'image' => '',
        'image_overlay_color' => '#000000',
        'image_overlay_opacity' => '0.5',
    ],
    'color' => [
        'text' => '#00173d',
        'button' => '#006eff',
        'button_text' => '#ffffff',
        'input_text' => '#00134d',
        'input_border' => '#006eff',
    ],
    'alignment' => [
        'heading' => 'center',
        'container' => 'center',
    ],
    'favicon' => [
        'light' => '/storage/auth/favicon.png',
        'dark' => '/storage/auth/favicon-dark.png',
    ],
];
