<?php

namespace TeamTeaTime\Forum\Actions;

use TeamTeaTime\Forum\Models\Post;

class UpdatePost extends BaseAction
{
    private Post $post;
    private string $content;

    public function __construct(Post $post, string $content)
    {
        $this->post = $post;
        $this->content = $content;
    }

    protected function transact()
    {
        $this->post->update(['content' => $this->content]);

        return $this->post;
    }
}
