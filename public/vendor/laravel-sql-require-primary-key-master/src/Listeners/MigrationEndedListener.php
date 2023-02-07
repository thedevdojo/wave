<?php

namespace ChrisHarvey\LaravelSqlRequirePrimaryKey\Listeners;

use ChrisHarvey\LaravelSqlRequirePrimaryKey\RequirePrimaryKeyState;
use Illuminate\Database\Events\MigrationEnded;

class MigrationEndedListener
{
    protected array $tables = [];

    public function handle(MigrationEnded $event)
    {
        $connection = $event->migration->getConnection();

        if (RequirePrimaryKeyState::connectionRequiresPrimaryKey($connection) === false) {
            return;
        }

        RequirePrimaryKeyState::restore($connection);
    }
}
