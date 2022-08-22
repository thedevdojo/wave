<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDatesToPaddleSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paddle_subscriptions', function (Blueprint $table) {
            $table->timestamp('last_payment_at')->nullable();
            $table->timestamp('next_payment_at')->nullable();
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
            //
        });
    }
}
