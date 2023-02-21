<?php

namespace TeamTeaTime\Forum\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAuthor
{
    public function author(): BelongsTo
    {
        $model = config('forum.integration.user_model');
        if (method_exists($model, 'withTrashed')) {
            return $this->belongsTo($model, 'author_id')->withTrashed();
        }

        return $this->belongsTo($model, 'author_id');
    }

    public function getAuthorNameAttribute(): ?string
    {
        $attribute = config('forum.integration.user_name');

        if ($this->author !== null) {
            return $this->author->$attribute;
        }

        return null;
    }
}
