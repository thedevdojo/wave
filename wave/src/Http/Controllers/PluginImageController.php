<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class PluginImageController extends Controller
{
    public function show($plugin_name)
    {
        $path = resource_path('plugins/'.$plugin_name.'/plugin.jpg');

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
