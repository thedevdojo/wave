<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class ProfileKeyValue extends Model
{
    protected $table = 'profile_key_values';

    public $timestamps = false;

    protected $fillable = [
        'type',
        'keyvalue_id',
        'keyvalue_type',
        'key',
        'value',
    ];

    public function profileKeyValue()
    {
        return $this->morphTo();
    }
}
