<?php

namespace Wave\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RedirectHomeToInstaller
{
    /**
     * A database without users hasn't been through the installer yet, so send the
     * home page there instead of rendering a site with no content.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('/') && ! $this->hasUsers()) {
            return redirect()->route('wave.install');
        }

        return $next($request);
    }

    protected function hasUsers(): bool
    {
        try {
            return User::query()->exists();
        } catch (QueryException) {
            // No tables yet: InstallMiddleware handles a missing database
            return true;
        }
    }
}
