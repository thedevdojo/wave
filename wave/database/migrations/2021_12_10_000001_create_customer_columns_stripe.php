<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerColumnsStripe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(config('payment.vendor') == 'stripe') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('stripe_id')->nullable()->index()->change();
                $table->renameColumn('card_brand', 'pm_type');
                $table->renameColumn('card_last_four', 'pm_last_four');
                $table->datetime('trial_ends_at')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(config('payment.vendor') == 'stripe') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('stripe_id')->nullable()->change();
                $table->renameColumn('pm_type', 'card_brand');
                $table->renameColumn('pm_last_four', 'card_last_four');
                $table->datetime('trial_ends_at')->nullable()->change();

                $table->dropIndex('users_stripe_id_index');
            });
        }
    }
}
