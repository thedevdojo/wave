<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Plan extends Model
{
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
