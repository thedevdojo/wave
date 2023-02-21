<?php

namespace TeamTeaTime\Forum\Http\Requests\Traits;

trait HandlesDeletion
{
    protected function isPermaDeleteRequested(): bool
    {
        return isset($this->validated()['permadelete']) && $this->validated()['permadelete'];
    }

    protected function isPermaDeleting(): bool
    {
        $softDeletesEnabled = config('forum.general.soft_deletes');

        return ! $softDeletesEnabled || ($softDeletesEnabled && $this->isPermaDeleteRequested());
    }
}
