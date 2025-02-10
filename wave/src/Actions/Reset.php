<?php

namespace Wave\Actions;

use Illuminate\Support\Facades\File;

class Reset
{
    public function __invoke()
    {
        File::delete(database_path('database.sqlite'));

        return redirect()->route('home');
    }
}
