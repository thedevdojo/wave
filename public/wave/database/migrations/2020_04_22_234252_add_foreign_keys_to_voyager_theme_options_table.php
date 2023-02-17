<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToVoyagerThemeOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		try {
			Schema::table('voyager_theme_options', function(Blueprint $table)
			{
				$table->foreign('theme_id', 'voyager_theme_options_ibfk_1')->references('id')->on('themes')->onUpdate('CASCADE')->onDelete('CASCADE');
			});
		} catch (\Exception $e) {
			\Log::error("Error adding foreign keys to voyager_theme_options table: ".$e->getMessage());
		}
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		try{
			Schema::table('theme_options', function(Blueprint $table)
			{
				$table->dropForeign('theme_options_theme_id_foreign');
			});
		} catch (\Exception $e) {
			\Log::error("Error removing foreign keys from voyager_theme_options table: ".$e->getMessage());
		}
	}

}
