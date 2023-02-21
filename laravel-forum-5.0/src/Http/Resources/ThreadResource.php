<?php

namespace TeamTeaTime\Forum\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use TeamTeaTime\Forum\Support\Api\ForumApi;

class ThreadResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'author_id' => $this->author_id,
            'author_name' => $this->author_name,
            'title' => $this->title,
            'pinned' => $this->pinned == 1,
            'locked' => $this->locked == 1,
            'first_post_id' => $this->first_post_id,
            'last_post_id' => $this->last_post_id,
            'reply_count' => $this->reply_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'actions' => [
                'post:lock' => ForumApi::route('thread.lock', ['thread' => $this->id]),
                'post:unlock' => ForumApi::route('thread.unlock', ['thread' => $this->id]),
                'post:pin' => ForumApi::route('thread.pin', ['thread' => $this->id]),
                'post:unpin' => ForumApi::route('thread.unpin', ['thread' => $this->id]),
                'post:rename' => ForumApi::route('thread.rename', ['thread' => $this->id]),
                'post:move' => ForumApi::route('thread.move', ['thread' => $this->id]),
                'delete:delete' => ForumApi::route('thread.delete', ['thread' => $this->id]),
                'post:restore' => ForumApi::route('thread.restore', ['thread' => $this->id]),
            ],
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'links' => [
                'self' => ForumApi::route('thread.fetch', ['thread' => $this->id]),
                'category' => ForumApi::route('category.fetch', ['category' => $this->category_id]),
                'posts' => ForumApi::route('thread.posts', ['thread' => $this->id]),
                'first_post_id' => ForumApi::route('post.fetch', ['post' => $this->first_post_id]),
                'last_post_id' => ForumApi::route('post.fetch', ['post' => $this->last_post_id]),
            ],
        ];
    }
}
