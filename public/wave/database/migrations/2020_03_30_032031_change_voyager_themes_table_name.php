<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeVoyagerThemesTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('themes')) {
            Schema::rename('voyager_themes', 'themes');
        }
        if (!Schema::hasTable('theme_options')) {
            Schema::rename('voyager_theme_options', 'theme_options');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('voyager_themes')) {
            Schema::rename('themes', 'voyager_themes');
        }
        if (!Schema::hasTable('voyager_theme_options')) {
            Schema::rename('theme_options', 'voyager_theme_options');
        }
    }
}
