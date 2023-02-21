<?php

namespace TeamTeaTime\Forum\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use TeamTeaTime\Forum\Models\Traits\HasAuthor;

class Thread extends BaseModel
{
    use SoftDeletes;
    use HasAuthor;

    protected $table = 'forum_threads';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'locked',
        'pinned',
        'reply_count',
        'first_post_id',
        'last_post_id',
        'updated_at',
    ];

    public const READERS_TABLE = 'forum_threads_read';

    public const STATUS_UNREAD = 'unread';
    public const STATUS_UPDATED = 'updated';

    private $currentReader = null;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->perPage = config('forum.general.pagination.threads');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function readers(): BelongsToMany
    {
        return $this->belongsToMany(
            config('forum.integration.user_model'),
            self::READERS_TABLE,
            'thread_id',
            'user_id'
        )->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function firstPost(): HasOne
    {
        return $this->hasOne(Post::class, 'id', 'first_post_id');
    }

    public function lastPost(): HasOne
    {
        return $this->hasOne(Post::class, 'id', 'last_post_id');
    }

    public function scopeRecent(Builder $query): Builder
    {
        $age = strtotime(config('forum.general.old_thread_threshold'), 0);
        $cutoff = time() - $age;

        return $query->where('updated_at', '>', date('Y-m-d H:i:s', $cutoff))->orderBy('updated_at', 'desc');
    }

    public function getIsOldAttribute(): bool
    {
        $age = config('forum.general.old_thread_threshold');

        return ! $age || $this->updated_at->timestamp < (time() - strtotime($age, 0));
    }

    public function getReaderAttribute()
    {
        if (! Auth::check()) {
            return null;
        }

        if ($this->currentReader === null) {
            $this->currentReader = $this->readers()->where('forum_threads_read.user_id', Auth::user()->getKey())->first();
        }

        return $this->currentReader !== null ? $this->currentReader->pivot : null;
    }

    public function getUserReadStatusAttribute(): ?string
    {
        if ($this->isOld || ! Auth::check()) {
            return null;
        }

        if ($this->reader === null) {
            return trans('forum::general.'.self::STATUS_UNREAD);
        }

        return $this->updatedSince($this->reader) ? trans('forum::general.'.self::STATUS_UPDATED) : null;
    }

    public function getPostCountAttribute(): int
    {
        return $this->reply_count + 1;
    }

    public function getLastPost(): Post
    {
        return $this->posts()->orderBy('created_at', 'desc')->first();
    }

    public function markAsRead(int $userId): void
    {
        if ($this->isOld) {
            return;
        }

        if ($this->reader === null) {
            $this->readers()->attach($userId);
        } elseif ($this->updatedSince($this->reader)) {
            $this->reader->touch();
        }
    }
}
