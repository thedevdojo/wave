<?php

namespace TeamTeaTime\Forum\Support\Api;

use Illuminate\Support\Str;

class ForumApi
{
    public static function route(string $route, array $params, bool $absolute = false)
    {
        $as = config('forum.api.router.as');

        if (! Str::startsWith($route, $as)) {
            $route = "{$as}{$route}";
        }

        return route($route, $params, $absolute);
    }
}
