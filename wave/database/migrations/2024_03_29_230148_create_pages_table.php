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
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing UNSIGNED INTEGER (primary key)
            $table->unsignedBigInteger('author_id'); // UNSIGNED INTEGER for the foreign key to users table
            $table->string('title', 191); // VARCHAR equivalent column
            $table->text('excerpt')->nullable(); // TEXT column, nullable for the excerpt
            $table->text('body'); // TEXT column for the main content
            $table->string('image', 191)->nullable(); // VARCHAR equivalent column, nullable for the image path
            $table->string('slug', 191)->unique(); // VARCHAR equivalent column with a unique index for the slug
            $table->text('meta_description')->nullable(); // TEXT column, nullable for the meta description
            $table->text('meta_keywords')->nullable(); // TEXT column, nullable for the meta keywords
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('INACTIVE'); // ENUM column for the status with a default value
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key constraint
            $table->foreign('author_id')->references('id')->on('users'); // Adjust if the users table or author_id column is named differently
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
