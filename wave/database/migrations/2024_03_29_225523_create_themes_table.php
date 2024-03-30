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
        Schema::create('themes', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing UNSIGNED INTEGER (primary key)
            $table->string('name', 255); // VARCHAR equivalent column
            $table->string('folder', 191)->unique(); // VARCHAR equivalent column with a unique index
            $table->boolean('active')->default(0); // TINYINT equivalent for boolean, with a default value
            $table->string('version', 255)->default(''); // VARCHAR equivalent column with a default value
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
