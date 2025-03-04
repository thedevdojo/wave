<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostCreditsToUsersAndPlansTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('post_credits')->default(0);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->integer('post_credits')->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('post_credits');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('post_credits');
        });
    }
}
