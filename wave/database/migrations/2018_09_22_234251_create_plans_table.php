<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plans', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->text('description', 65535)->nullable();
			$table->string('features');
			$table->string('plan_id')->default('');
			$table->bigInteger('role_id')->unsigned()->index('plans_role_id_foreign');
			$table->boolean('default')->default(0);
			$table->string('price');
			$table->integer('trial_days')->default(0);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('plans');
	}

}
