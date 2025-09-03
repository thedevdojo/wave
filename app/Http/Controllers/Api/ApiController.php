<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function posts()
    {
        return Post::all();
    }
}
