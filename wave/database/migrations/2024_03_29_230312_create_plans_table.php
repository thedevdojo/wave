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
            $table->string('name', 191); // VARCHAR equivalent column
            $table->string('slug', 191)->unique(); // VARCHAR equivalent column with a unique constraint
            $table->text('description')->nullable(); // TEXT column, nullable for the description
            $table->string('features', 191); // VARCHAR equivalent column for features
            $table->string('plan_id', 191)->default(''); // VARCHAR equivalent column with a default value
            $table->unsignedBigInteger('role_id'); // UNSIGNED BIGINT for the foreign key
            $table->boolean('default')->default(0); // TINYINT equivalent column for a boolean, with a default value
            $table->string('price', 191); // VARCHAR equivalent column for the price
            $table->integer('trial_days')->default(0); // INTEGER column with a default value
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
