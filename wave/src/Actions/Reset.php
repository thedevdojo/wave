<?php

namespace Wave\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Reset
{
    public function __invoke()
    {
        // Only allow in local environment
        if (! app()->environment('local')) {
            abort(403, 'Reset is only available in local development.');
        }

        // Require authentication
        if (! auth()->check()) {
            abort(403, 'Authentication required.');
        }

        $databasePath = database_path('database.sqlite');

        // Check if database exists
        if (! File::exists($databasePath)) {
            abort(404, 'Database file not found.');
        }

        // Logout user to prevent query errors after deletion
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Delete the database
        File::delete($databasePath);

        return redirect('/');
    }
}
