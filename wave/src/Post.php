<?php

namespace Wave;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    public $guarded = [];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\PostFactory::new();
    }

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
