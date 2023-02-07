<?php

namespace ChrisHarvey\LaravelSqlRequirePrimaryKey;

use Illuminate\Support\Facades\DB;

class RequirePrimaryKeyState
{
    protected static array $originals = [];

    public static function set(bool $status, ?string $connection = null): void
    {
        if (! static::hasOriginal($connection)) {
            static::saveOriginal();
        }

        DB::statement('/*!80013 SET SQL_REQUIRE_PRIMARY_KEY = ?;*/', [$status]);
    }

    public static function get(?string $connection = null): bool
    {
        $requiresPrimaryKey = DB::connection($connection)
            ->selectOne('SHOW SESSION VARIABLES LIKE "sql_require_primary_key";')->Value ?? 'OFF';

        return $requiresPrimaryKey == 'ON';
    }

    public static function hasOriginal(?string $connection = null): bool
    {
        return isset(static::$originals[$connection]);
    }

    public static function getOriginal(?string $connection = null): bool
    {
        return static::$originals[$connection];
    }

    public static function saveOriginal(?string $connection = null): void
    {
        static::$originals[$connection] = static::get($connection);
    }

    public static function restore(?string $connection = null): void
    {
        static::set(static::$originals[$connection], $connection);
    }

    public static function connectionRequiresPrimaryKey(?string $connection = null): bool
    {
        $connectionKey = $connection === null ? config('database.default') : $connection;

        return config("database.connections.{$connectionKey}.require_primary_key", false);
    }
}