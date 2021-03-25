<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaddleSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paddle_subscriptions', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('subscription_id')->unsigned()->unique();
			$table->integer('plan_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('status')->nullable();
			$table->string('update_url')->nullable();
			$table->string('cancel_url')->nullable();
			$table->dateTime('cancelled_at')->nullable();
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
        Schema::dropIfExists('paddle_subscriptions');
    }
}
