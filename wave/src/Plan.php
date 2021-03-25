<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Role;

class Plan extends Model
{
    public function role() {
        return $this->belongsTo(Role::class);
    }
}
