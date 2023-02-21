<?php

namespace TeamTeaTime\Forum\Policies;

use TeamTeaTime\Forum\Models\Thread;

class ThreadPolicy
{
    public function view($user, Thread $thread): bool
    {
        return true;
    }

    public function deletePosts($user, Thread $thread): bool
    {
        return true;
    }

    public function restorePosts($user, Thread $thread): bool
    {
        return true;
    }

    public function rename($user, Thread $thread): bool
    {
        return $user->getKey() === $thread->author_id;
    }

    public function reply($user, Thread $thread): bool
    {
        return ! $thread->locked;
    }

    public function delete($user, Thread $thread): bool
    {
        return $user->getKey() === $thread->author_id;
    }

    public function restore($user, Thread $thread): bool
    {
        return $user->getKey() === $thread->author_id;
    }
}
