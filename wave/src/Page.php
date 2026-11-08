<?php

namespace Wave;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $guarded = [];

    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'ACTIVE');
    }

    public function link()
    {
        return url($this->slug);
    }

    public function image()
    {
        return url($this->image);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
