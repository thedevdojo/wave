<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
