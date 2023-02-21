<?php

namespace TeamTeaTime\Forum\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use TeamTeaTime\Forum\Support\Api\ForumApi;

class PostResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'thread_id' => $this->thread_id,
            'author_id' => $this->author_id,
            'author_name' => $this->author_name,
            'content' => $this->content,
            'post_id' => $this->post_id,
            'sequence' => $this->sequence,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'actions' => [
                'patch:update' => ForumApi::route('post.update', ['post' => $this->id]),
                'delete:delete' => ForumApi::route('post.delete', ['post' => $this->id]),
                'post:restore' => ForumApi::route('post.restore', ['post' => $this->id]),
            ],
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        $links = [
            'self' => ForumApi::route('post.fetch', ['post' => $this->id]),
            'thread' => ForumApi::route('thread.fetch', ['thread' => $this->thread_id]),
        ];

        if ($this->post_id != null) {
            $links['parent'] = ForumApi::route('post.fetch', ['post' => $this->post_id]);
        }

        return compact('links');
    }
}
