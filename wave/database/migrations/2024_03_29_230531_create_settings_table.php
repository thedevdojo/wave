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
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing UNSIGNED INTEGER (primary key)
            $table->string('key', 191)->unique(); // VARCHAR equivalent column with a unique constraint
            $table->string('display_name', 191); // VARCHAR equivalent column
            $table->text('value')->nullable(); // TEXT column, nullable for the setting's value
            $table->text('details')->nullable(); // TEXT column, nullable for any details about the setting
            $table->string('type', 191); // VARCHAR equivalent column for the type of setting
            $table->integer('order')->default(1); // INTEGER column with a default value
            $table->string('group', 191)->nullable(); // VARCHAR equivalent column, nullable for grouping settings
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
