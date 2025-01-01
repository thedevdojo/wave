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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the form for identification
            $table->string('slug')->unique(); // Unique slug for the form URL
            $table->json('fields'); // JSON column to store the form fields configuration
            $table->boolean('is_active')->default(true); // To activate or deactivate the form
            $table->timestamps(); // Timestamps for created_at and updated_at
        });

        Schema::create('form_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade'); // Foreign key linking to the forms table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key linking to the users table
            $table->json('data'); // JSON column to store the form submission data
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_entries');
        Schema::dropIfExists('forms');
    }
};
