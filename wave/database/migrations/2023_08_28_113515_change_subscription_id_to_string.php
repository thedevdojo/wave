<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSubscriptionIdToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paddle_subscriptions', function (Blueprint $table) {
            // Change subscription_id and plan_id columns to string type
            $table->string('subscription_id', 255)->change();
            $table->string('plan_id', 255)->change();
            // Adjusting the length of the cancel_url and update_url columns to 500 (or a value that suits your needs)
            $table->text('cancel_url')->change();
            $table->text('update_url')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paddle_subscriptions', function (Blueprint $table) {
            // Revert back to integer type if needed
            $table->integer('subscription_id')->change();
            $table->integer('plan_id')->change();
            // Reverting the length back to 255
            $table->string('cancel_url', 255)->change();
            $table->string('update_url', 255)->change();
        });
    }
}
