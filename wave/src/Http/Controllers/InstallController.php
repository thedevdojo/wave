<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;

class InstallController extends Controller
{
    public function __invoke()
    {
        if ($this->installed()) {
            return redirect('/');
        }

        return view('wave::install');
    }

    protected function installed(): bool
    {
        try {
            return User::query()->exists();
        } catch (QueryException) {
            return false;
        }
    }
}
