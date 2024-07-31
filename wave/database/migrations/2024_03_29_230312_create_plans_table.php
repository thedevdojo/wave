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
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing UNSIGNED INTEGER (primary key)
            $table->string('name'); // VARCHAR equivalent column
            $table->text('description')->nullable(); // TEXT column, nullable for the description
            $table->string('features'); // VARCHAR equivalent column for features
            $table->string('monthly_price_id')->nullable();
            $table->string('yearly_price_id')->nullable();
            $table->string('onetime_price_id')->nullable();
            $table->boolean('active')->default(1);
            $table->unsignedBigInteger('role_id'); // UNSIGNED BIGINT for the foreign key
            $table->boolean('default')->default(0); // TINYINT equivalent column for a boolean, with a default value
            $table->string('monthly_price')->nullable(); // VARCHAR equivalent column for the price
            $table->string('yearly_price')->nullable(); // VARCHAR equivalent column for the price
            $table->string('onetime_price')->nullable(); // VARCHAR equivalent column for the price
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key constraint
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
