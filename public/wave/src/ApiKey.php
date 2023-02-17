<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
	protected $table = 'api_keys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'key',
        'last_used_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_used_at' => 'datetime'
    ];

    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

}
