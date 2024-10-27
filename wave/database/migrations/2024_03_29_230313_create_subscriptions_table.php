<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable');
            $table->unsignedInteger('plan_id');
            $table->string('vendor_slug');
            $table->string('vendor_product_id')->nullable();
            $table->string('vendor_transaction_id')->nullable();
            $table->string('vendor_customer_id')->nullable();
            $table->string('vendor_subscription_id')->nullable();
            $table->string('status');
            $table->enum('cycle', ['month', 'year', 'onetime'])->default('month');
            $table->integer('seats')->default(1);
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['billable_id', 'billable_type', 'plan_id']);
            $table->unique(['vendor_slug', 'vendor_subscription_id']);

            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
