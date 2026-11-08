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
        Schema::create('theme_options', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing UNSIGNED INTEGER (primary key)
            $table->unsignedInteger('theme_id'); // UNSIGNED INTEGER for the foreign key to the themes table
            $table->string('key', 255); // VARCHAR equivalent column for the option key
            $table->text('value')->nullable(); // TEXT column, nullable for the option value
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key constraint
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade'); // Ensure to adjust the onDelete action as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_options');
    }
};
