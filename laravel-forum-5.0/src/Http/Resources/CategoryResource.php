<?php

namespace TeamTeaTime\Forum\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use TeamTeaTime\Forum\Support\Api\ForumApi;

class CategoryResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'accepts_threads' => $this->accepts_threads == 1,
            'newest_thread_id' => $this->newest_thread_id,
            'latest_active_thread_id' => $this->latest_active_thread_id,
            'thread_count' => $this->thread_count,
            'post_count' => $this->post_count,
            'is_private' => $this->is_private == 1,
            'left' => $this->_lft,
            'right' => $this->_rgt,
            'parent_id' => $this->parent_id,
            'color' => $this->color,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'actions' => [
                'patch:update' => ForumApi::route('category.update', ['category' => $this->id]),
                'delete:delete' => ForumApi::route('category.delete', ['category' => $this->id]),
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
            'self' => ForumApi::route('category.fetch', ['category' => $this->id]),
        ];

        if ($this->newest_thread_id != null) {
            $links['newest_thread'] = ForumApi::route('thread.fetch', ['thread' => $this->newest_thread_id]);
        }

        if ($this->latest_active_thread_id != null) {
            $links['latest_active_thread'] = ForumApi::route('thread.fetch', ['thread' => $this->latest_active_thread_id]);
        }

        return compact('links');
    }
}
