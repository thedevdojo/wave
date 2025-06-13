<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function posts()
    {
        return \App\Models\Post::all();
    }
}
