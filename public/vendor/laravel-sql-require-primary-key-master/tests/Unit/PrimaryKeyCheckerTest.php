<?php

namespace ChrisHarvey\LaravelSqlRequirePrimaryKey\Tests\Unit;

use ChrisHarvey\LaravelSqlRequirePrimaryKey\Exceptions\PrimaryKeyRequiredException;
use ChrisHarvey\LaravelSqlRequirePrimaryKey\PrimaryKeyChecker;
use ChrisHarvey\LaravelSqlRequirePrimaryKey\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrimaryKeyCheckerTest extends TestCase
{
    public function testCanCreateTableWithStringPrimaryKey()
    {
        $queries = DB::pretend(function () {
            Schema::create('foo', function (Blueprint $table) {
                $table->string('id')->primary();
            });
        });

        PrimaryKeyChecker::check($queries);

        $this->assertCount(2, $queries);
    }

    public function testCanCreateTableWithIncrementingPrimaryKey()
    {
        $queries = DB::pretend(function () {
            Schema::create('foo', function (Blueprint $table) {
                $table->id('id');
            });
        });

        PrimaryKeyChecker::check($queries);

        $this->assertCount(1, $queries);
    }

    public function testCanAlterTableWithoutSpecifyingPrimaryKey()
    {
        $queries = DB::pretend(function () {
            Schema::table('foo', function (Blueprint $table) {
                $table->string('bar');
            });
        });

        PrimaryKeyChecker::check($queries);

        $this->assertCount(1, $queries);
    }

    public function testCanNotDropPrimaryKey()
    {
        $this->expectException(PrimaryKeyRequiredException::class);

        $queries = DB::pretend(function () {
            Schema::table('foo', function (Blueprint $table) {
                $table->dropPrimary('id');
            });
        });

        PrimaryKeyChecker::check($queries);

        $this->assertCount(1, $queries);
    }

    public function testCanAssignNewPrimaryKey()
    {
        $queries = DB::pretend(function () {
            Schema::table('foo', function (Blueprint $table) {
                $table->dropPrimary('id');
                $table->string('bar')->primary();
            });
        });

        PrimaryKeyChecker::check($queries);

        $this->assertCount(3, $queries);
    }

    public function testCanNotCreateTableWithoutPrimaryKey()
    {
        $this->expectException(PrimaryKeyRequiredException::class);

        $queries = DB::pretend(function () {
            Schema::create('foo', function (Blueprint $table) {
                $table->string('id');
            });
        });

        PrimaryKeyChecker::check($queries);

        $this->assertCount(2, $queries);
    }
}