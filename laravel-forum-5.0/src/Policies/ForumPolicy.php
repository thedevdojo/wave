<?php

namespace TeamTeaTime\Forum\Policies;

class ForumPolicy
{
    public function createCategories($user): bool
    {
        return true;
    }

    public function manageCategories($user): bool
    {
        return $this->moveCategories($user) ||
               $this->renameCategories($user);
    }

    public function moveCategories($user): bool
    {
        return true;
    }

    public function renameCategories($user): bool
    {
        return true;
    }

    public function markThreadsAsRead($user): bool
    {
        return true;
    }

    public function viewTrashedThreads($user): bool
    {
        return true;
    }

    public function viewTrashedPosts($user): bool
    {
        return true;
    }
}
