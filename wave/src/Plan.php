<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class Plan extends Model
{
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
