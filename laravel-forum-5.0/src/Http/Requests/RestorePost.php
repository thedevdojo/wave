<?php

namespace TeamTeaTime\Forum\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TeamTeaTime\Forum\Actions\RestorePost as Action;
use TeamTeaTime\Forum\Events\UserRestoredPost;
use TeamTeaTime\Forum\Interfaces\FulfillableRequest;

class RestorePost extends FormRequest implements FulfillableRequest
{
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $this->user()->can('restorePosts', $post->thread) && $this->user()->can('restore', $post);
    }

    public function rules(): array
    {
        return [];
    }

    public function fulfill()
    {
        $action = new Action($this->route('post'));
        $post = $action->execute();

        if (! $post === null) {
            UserRestoredPost::dispatch($this->user(), $post);
        }

        return $post;
    }
}
