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
        Schema::create('profile_key_values', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing UNSIGNED INTEGER (primary key)
            $table->string('type', 191); // VARCHAR equivalent column for the type
            $table->unsignedInteger('keyvalue_id'); // UNSIGNED INTEGER for the key-value relationship ID
            $table->string('keyvalue_type', 191); // VARCHAR equivalent column for the type of key-value relationship
            $table->string('key', 191); // VARCHAR equivalent column for the key
            $table->text('value'); // VARCHAR equivalent column for the value
            $table->timestamps(); // Adds created_at and updated_at columns

            // Unique constraint to ensure uniqueness across the combination of keyvalue_id, keyvalue_type, and key
            $table->unique(['keyvalue_id', 'keyvalue_type', 'key'], 'profile_key_values_keyvalue_type_key_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_key_values');
    }
};
