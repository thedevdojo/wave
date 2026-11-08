<?php

use Illuminate\Support\Facades\Facade;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

return [

    'aliases' => Facade::defaultAliases()->merge([
        'JWTAuth' => JWTAuth::class,
        'JWTFactory' => JWTFactory::class,
    ])->toArray(),

];
