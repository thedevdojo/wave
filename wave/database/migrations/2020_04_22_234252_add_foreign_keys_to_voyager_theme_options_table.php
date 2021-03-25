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
		Schema::table('theme_options', function(Blueprint $table)
		{
			$table->foreign('theme_id')->references('id')->on('themes')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('theme_options', function(Blueprint $table)
		{
			$table->dropForeign('theme_options_theme_id_foreign');
		});
	}

}
