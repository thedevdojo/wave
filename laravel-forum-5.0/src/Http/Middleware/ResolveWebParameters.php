<?php

namespace TeamTeaTime\Forum\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;

class ResolveWebParameters
{
    /**
     * Resolve forum web route parameters.
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
            $category = Category::find($parameters['category']);

            if ($category === null) {
                throw new NotFoundHttpException("Failed to resolve 'category' route parameter.");
            }

            $request->route()->setParameter('category', $category);
        }

        if (array_key_exists('thread', $parameters)) {
            $query = Thread::with('category');

            if (Gate::allows('viewTrashedThreads')) {
                $query->withTrashed();
            }

            $thread = $query->find($parameters['thread']);

            if ($thread === null) {
                throw new NotFoundHttpException("Failed to resolve 'thread' route parameter.");
            }

            $request->route()->setParameter('thread', $thread);
        }

        if (array_key_exists('post', $parameters)) {
            $query = Post::with(['thread', 'thread.category']);

            if (Gate::allows('viewTrashedPosts')) {
                $query->withTrashed();
            }

            $post = $query->find($parameters['post']);

            if ($post === null) {
                throw new NotFoundHttpException("Failed to resolve 'post' route parameter.");
            }

            $request->route()->setParameter('post', $post);
        }

        return $next($request);
    }
}
