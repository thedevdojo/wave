<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Wave\Changelog;

class ChangelogController extends Controller
{
    public function read()
    {
        $user = auth()->user();
        Changelog::whereDoesntHave('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get()
            ->pluck('id')
            ->tap(function ($missingChangelogNotifications) use ($user) {
                $user->changelogs()->attach($missingChangelogNotifications->toArray());
            });
    }
}
