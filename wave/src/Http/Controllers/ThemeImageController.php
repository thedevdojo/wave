<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ThemeImageController extends Controller
{
    public function show($theme_name)
    {
        $path = config('themes.folder').'/'.$theme_name.'/theme.jpg';

        if (! File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = response($file);
        $response->header('Content-Type', $type);

        return $response;
    }
}
