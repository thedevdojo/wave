<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;


class KeyValue extends Model
{
	protected $table = 'wave_key_values';

 	public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'keyvalue_id',
        'keyvalue_type',
        'key',
        'value',
    ];

    public function keyvalue()
    {
        return $this->morphTo();
    }
}
