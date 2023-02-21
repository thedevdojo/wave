<?php

namespace TeamTeaTime\Forum\Actions;

use TeamTeaTime\Forum\Models\Category;

class CreateCategory extends BaseAction
{
    private string $title;
    private string $description;
    private string $color;
    private bool $acceptsThreads;
    private bool $isPrivate;

    public function __construct(string $title, string $description, string $color, bool $acceptsThreads = true, bool $isPrivate = false)
    {
        $this->title = $title;
        $this->description = $description;
        $this->color = $color;
        $this->acceptsThreads = $acceptsThreads;
        $this->isPrivate = $isPrivate;
    }

    protected function transact()
    {
        return Category::create([
            'title' => $this->title,
            'description' => $this->description,
            'color' => $this->color,
            'accepts_threads' => $this->acceptsThreads,
            'is_private' => $this->isPrivate,
        ]);
    }
}
