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
        Schema::create('paddle_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id'); // Big auto-incrementing UNSIGNED INTEGER (primary key)
            $table->string('subscription_id')->unique(); // Unique VARCHAR equivalent column for the subscription ID
            $table->string('plan_id')->nullable(); // Nullable VARCHAR equivalent column for the plan ID
            $table->unsignedBigInteger('user_id')->nullable(); // Nullable UNSIGNED INTEGER for the foreign key referencing the users table
            $table->string('status')->nullable(); // Nullable VARCHAR equivalent column for the subscription status
            $table->text('update_url')->nullable(); // Nullable TEXT column for the update URL
            $table->text('cancel_url')->nullable(); // Nullable TEXT column for the cancel URL
            $table->dateTime('cancelled_at')->nullable(); // Nullable DATETIME column for the cancellation time
            $table->timestamps(); // Adds created_at and updated_at columns
            $table->timestamp('last_payment_at')->nullable(); // Nullable TIMESTAMP column for the last payment time
            $table->timestamp('next_payment_at')->nullable(); // Nullable TIMESTAMP column for the next payment time

            // Assuming 'user_id' should reference 'id' on the 'users' table; adjust if necessary
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paddle_subscriptions');
    }
};
