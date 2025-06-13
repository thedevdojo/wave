<?php

namespace Wave;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    protected $fillable = ['title', 'description', 'body'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany('Wave\User');
    }
}
