<?php

namespace Wave\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Folio\Events\ViewMatched;
use Wave\Post;

/**
 * Any Folio page that binds an unpublished Post 404s for everyone but admins,
 * so a page letting authors preview their own drafts needs to account for this.
 */
class HideUnpublishedPosts
{
    /**
     * Folio binds page models before the session has started, so we can't
     * tell yet whether the viewer is an admin. Flag the request instead.
     */
    public function flag(ViewMatched $event): void
    {
        $unpublished = collect($event->matchedView->data)
            ->contains(fn ($value) => $value instanceof Post && $value->status !== 'PUBLISHED');

        request()->attributes->set('wave.unpublished_post', $unpublished);
    }

    public function handle(Request $request, Closure $next)
    {
        abort_if($request->attributes->get('wave.unpublished_post') && ! $request->user()?->isAdmin(), 404);

        return $next($request);
    }
}
