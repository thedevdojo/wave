<?php

namespace TeamTeaTime\Forum\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;

class ResolveApiParameters
{
    /**
     * Resolve forum API route parameters.
     * This logic was originally applied using Route::bind, but moved here to scope it to the package routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $parameters = $request->route()->parameters();

        if (array_key_exists('category', $parameters)) {
            $request->route()->setParameter('category', Category::find($parameters['category']));
        }

        if (array_key_exists('thread', $parameters)) {
            $query = Thread::with('category');

            if (Gate::allows('viewTrashedThreads')) {
                $query->withTrashed();
            }

            $request->route()->setParameter('thread', $query->find($parameters['thread']));
        }

        if (array_key_exists('post', $parameters)) {
            $query = Post::with(['thread', 'thread.category']);

            if (Gate::allows('viewTrashedPosts')) {
                $query->withTrashed();
            }

            $request->route()->setParameter('post', $query->find($parameters['post']));
        }

        return $next($request);
    }
}
