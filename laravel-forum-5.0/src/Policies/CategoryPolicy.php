<?php

namespace TeamTeaTime\Forum\Policies;

use TeamTeaTime\Forum\Models\Category;

class CategoryPolicy
{
    public function createThreads($user, Category $category): bool
    {
        return true;
    }

    public function manageThreads($user, Category $category): bool
    {
        return $this->deleteThreads($user, $category) ||
               $this->restoreThreads($user, $category) ||
               $this->moveThreadsFrom($user, $category) ||
               $this->lockThreads($user, $category) ||
               $this->pinThreads($user, $category);
    }

    public function deleteThreads($user, Category $category): bool
    {
        return true;
    }

    public function restoreThreads($user, Category $category): bool
    {
        return true;
    }

    public function enableThreads($user, Category $category): bool
    {
        return false;
    }

    public function moveThreadsFrom($user, Category $category): bool
    {
        return false;
    }

    public function moveThreadsTo($user, Category $category): bool
    {
        return false;
    }

    public function lockThreads($user, Category $category): bool
    {
        return false;
    }

    public function pinThreads($user, Category $category): bool
    {
        return false;
    }

    public function markThreadsAsRead($user, Category $category): bool
    {
        return true;
    }

    public function view($user, Category $category): bool
    {
        return true;
    }

    public function delete($user, Category $category): bool
    {
        return false;
    }
}
