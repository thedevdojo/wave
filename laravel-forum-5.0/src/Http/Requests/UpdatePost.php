<?php

namespace TeamTeaTime\Forum\Http\Requests;

use TeamTeaTime\Forum\Actions\UpdatePost as Action;
use TeamTeaTime\Forum\Events\UserUpdatedPost;

class UpdatePost extends CreatePost
{
    public function authorize(): bool
    {
        return $this->user()->can('edit', $this->route('post'));
    }

    public function fulfill()
    {
        $input = $this->validated();
        $action = new Action($this->route('post'), $input['content']);
        $post = $action->execute();

        UserUpdatedPost::dispatch($this->user(), $post);

        return $post;
    }
}
