<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Workspace;

class WorkspaceContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for unauthenticated users
        if (!auth()->check()) {
            return $next($request);
        }
        
        $user = auth()->user();
        $workspaceId = session('current_workspace_id');
        
        // If there's a workspace ID in the session, verify it's valid for this user
        if ($workspaceId) {
            $workspace = Workspace::find($workspaceId);
            
            // If workspace doesn't exist or user doesn't have access, clear the session
            if (!$workspace || 
                ($workspace->user_id !== $user->id && 
                 !$workspace->members()->where('user_id', $user->id)->exists())) {
                session()->forget('current_workspace_id');
                $workspaceId = null;
            }
        }
        
        // Share workspace context with views
        view()->share('currentWorkspaceId', $workspaceId);
        
        if ($workspaceId) {
            $workspace = Workspace::find($workspaceId);
            view()->share('currentWorkspace', $workspace);
        } else {
            view()->share('currentWorkspace', null);
        }
        
        return $next($request);
    }
}
