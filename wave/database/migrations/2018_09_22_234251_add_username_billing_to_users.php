<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUsernameBillingToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->string('username')->unique();
			$table->string('stripe_id')->nullable();
			$table->string('card_brand')->nullable();
			$table->string('card_last_four')->nullable();
			$table->dateTime('trial_ends_at')->nullable();
			$table->string('verification_code')->nullable();
			$table->boolean('verified')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('username');
			$table->dropColumn('stripe_id');
			$table->dropColumn('card_brand');
			$table->dropColumn('card_last_four');
			$table->dropColumn('trial_ends_at');
			$table->dropColumn('verification_code');
			$table->dropColumn('verified');
		});
	}

}
