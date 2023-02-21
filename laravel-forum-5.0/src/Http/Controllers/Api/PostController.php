<?php

namespace TeamTeaTime\Forum\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use TeamTeaTime\Forum\Http\Requests\CreatePost;
use TeamTeaTime\Forum\Http\Requests\DeletePost;
use TeamTeaTime\Forum\Http\Requests\RestorePost;
use TeamTeaTime\Forum\Http\Requests\SearchPosts;
use TeamTeaTime\Forum\Http\Requests\UpdatePost;
use TeamTeaTime\Forum\Http\Resources\PostResource;
use TeamTeaTime\Forum\Models\Post;

class PostController extends BaseController
{
    protected $resourceClass = null;

    public function __construct()
    {
        $this->resourceClass = config('forum.api.resources.post', PostResource::class);
    }

    public function indexByThread(Request $request): AnonymousResourceCollection|Response
    {
        $thread = $request->route('thread');
        if (! $thread->category->isAccessibleTo($request->user())) {
            return $this->notFoundResponse();
        }

        if ($thread->category->is_private) {
            $this->authorize('view', $thread);
        }

        return $this->resourceClass::collection($thread->posts()->paginate());
    }

    public function search(SearchPosts $request): AnonymousResourceCollection
    {
        $posts = $request->fulfill();

        return $this->resourceClass::collection($posts);
    }

    public function recent(Request $request, bool $unreadOnly = false): AnonymousResourceCollection
    {
        $posts = Post::recent()
            ->get()
            ->filter(function (Post $post) use ($request, $unreadOnly) {
                return $post->thread->category->isAccessibleTo($request->user())
                    && (! $unreadOnly || $post->thread->reader === null || $post->updatedSince($post->thread->reader))
                    && (
                        ! $post->thread->category->is_private
                        || $request->user()
                        && $request->user()->can('view', $post->thread)
                    );
            });

        return $this->resourceClass::collection($posts);
    }

    public function unread(Request $request): AnonymousResourceCollection
    {
        return $this->recent($request, true);
    }

    public function fetch(Request $request): JsonResource|Response
    {
        $post = $request->route('post');
        if (! $post->thread->category->isAccessibleTo($request->user())) {
            return $this->notFoundResponse();
        }

        if ($post->thread->category->is_private) {
            $this->authorize('view', $post->thread);
        }

        return new $this->resourceClass($post);
    }

    public function store(CreatePost $request): JsonResource
    {
        $post = $request->fulfill();

        return new $this->resourceClass($post);
    }

    public function update(UpdatePost $request): JsonResource
    {
        $post = $request->fulfill();

        return new $this->resourceClass($post);
    }

    public function delete(DeletePost $request): Response
    {
        $post = $request->fulfill();

        if ($post === null) {
            return $this->invalidSelectionResponse();
        }

        return new Response(new $this->resourceClass($post));
    }

    public function restore(RestorePost $request): Response
    {
        $post = $request->fulfill();

        if ($post === null) {
            return $this->invalidSelectionResponse();
        }

        return new Response(new $this->resourceClass($post));
    }
}
