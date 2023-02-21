<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddDefaultsToForumTableThreadsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->boolean('pinned')->nullable()->default(0)->change();
            $table->boolean('locked')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->boolean('pinned')->nullable(false)->default(null)->change();
            $table->boolean('locked')->nullable(false)->default(null)->change();
        });
    }
}
