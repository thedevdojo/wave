<?php

namespace Wave;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

    public function link()
    {
        return url('p/'.$this->slug);
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
