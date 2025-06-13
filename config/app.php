<?php

use Illuminate\Support\Facades\Facade;

return [

    'aliases' => Facade::defaultAliases()->merge([
        'Image' => Intervention\Image\Facades\Image::class,
        'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
    ])->toArray(),

];
