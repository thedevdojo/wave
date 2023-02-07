<?php

namespace ChrisHarvey\LaravelSqlRequirePrimaryKey\Listeners;

use ChrisHarvey\LaravelSqlRequirePrimaryKey\PrimaryKeyChecker;
use ChrisHarvey\LaravelSqlRequirePrimaryKey\RequirePrimaryKeyState;
use Illuminate\Database\Events\MigrationStarted;
use Illuminate\Support\Facades\DB;

class MigrationStartedListener
{
    protected array $tables = [];

    public function handle(MigrationStarted $event)
    {
        $migration = $event->migration;
        $method = $event->method;

        $connection = $migration->getConnection();

        if (RequirePrimaryKeyState::connectionRequiresPrimaryKey($connection) === false) {
            return;
        }

        $skipPrimaryKeyChecks = $migration->skipPrimaryKeyChecks ?? false;

        if ($skipPrimaryKeyChecks === false) {
            $queries = DB::connection($connection)->pretend(function () use ($migration, $method) {
                $migration->$method();
            });

            PrimaryKeyChecker::check($queries);
        }

        RequirePrimaryKeyState::set(false, $connection);
    }
}
