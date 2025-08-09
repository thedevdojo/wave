<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    public $guarded = [];

    public function link()
    {
        return url('/blog/'.$this->category->slug.'/'.$this->slug);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('\Wave\User', 'author_id');
    }

    public function image()
    {
        return Storage::disk(config('filament.default_filesystem_disk'))->url($this->image);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo('Wave\Category');
    }
}
