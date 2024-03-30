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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id'); // Unsigned BIGINT for the user_id
            $table->unsignedBigInteger('role_id'); // Unsigned BIGINT for the role_id

            // Primary key for the combination of user_id and role_id to ensure uniqueness
            $table->primary(['user_id', 'role_id']);

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Adjust the onDelete() as per your requirement
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade'); // Adjust the onDelete() as per your requirement

            // Optionally, you can add indexes for 'user_id' and 'role_id' for better performance
            $table->index('user_id');
            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
