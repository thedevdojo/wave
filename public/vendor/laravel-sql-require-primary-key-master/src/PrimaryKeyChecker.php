<?php

namespace ChrisHarvey\LaravelSqlRequirePrimaryKey;

use ChrisHarvey\LaravelSqlRequirePrimaryKey\Exceptions\PrimaryKeyRequiredException;

class PrimaryKeyChecker
{
    protected array $queries;
    protected array $tables = [];

    public function __construct(array $queries)
    {
        $this->queries = $queries;
    }

    public static function check(array $queries)
    {
        (new static($queries))->runChecks();
    }

    public function runChecks()
    {
        foreach ($this->queries as $query) {
            $query = $query['query'];
            $table = $this->getTableForQuery($query);

            if ($this->queryIsAlteringTable($query) && ! $this->tableWasCreated($table)) {
                $this->tables[$table]['primary'] = true;

                if ($this->queryIsDroppingPrimaryKey($query)) {
                    $this->tables[$table]['primary'] = false;
                }
            }

            if ($this->queryIsCreatingTable($query)) {
                $this->tables[$table]['created'] = true;
            }

            if ($this->queryHasPrimaryKey($query)) {
                $this->tables[$table]['primary'] = true;
            }
        }

        foreach ($this->tables as $table => $operations) {
            $tableHasPrimary = $operations['primary'] ?? false;

            if (! $tableHasPrimary) {
                throw new PrimaryKeyRequiredException("The '{$table}' table must have a primary key");
            }
        }
    }

    protected function tableWasCreated(string $table): bool
    {
        return $this->tables[$table]['created'] ?? false;
    }

    public function queryHasPrimaryKey(string $query): bool
    {
        if ($this->queryIsDroppingPrimaryKey($query)) {
            return false;
        }

        return preg_match('/primary key/i', $query);
    }

    public function queryIsDroppingPrimaryKey(string $query): bool
    {
        return preg_match('/drop primary key/i', $query);
    }

    public function queryIsCreatingTable(string $query): bool
    {
        return preg_match('/create table/i', $query);
    }

    public function queryIsAlteringTable(string $query): bool
    {
        return preg_match('/alter table/i', $query);
    }

    public function getTableForQuery(string $query): ?string
    {
        if (preg_match('/(create|alter) table( `| )([a-z_-]*)(`| )/i', $query, $matches)) {
            return $matches[3];
        }

        return null;
    }
}