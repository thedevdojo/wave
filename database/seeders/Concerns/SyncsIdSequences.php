<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\DB;

trait SyncsIdSequences
{
    /**
     * Postgres doesn't move an id sequence past rows inserted with explicit ids,
     * so without this the next row created in that table reuses an existing id.
     */
    protected function syncIdSequence(string $table): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement(
            "SELECT setval(pg_get_serial_sequence(?, 'id'), COALESCE((SELECT MAX(id) FROM \"{$table}\"), 0) + 1, false)",
            [$table]
        );
    }
}
