<?php

namespace Wave;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = [];

    public $timestamps = false;

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('wave_settings');
        });

        static::deleted(function () {
            Cache::forget('wave_settings');
        });
    }

    public static function get($key, $default = null)
    {
        $settings = Cache::rememberForever('wave_settings', function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

}
