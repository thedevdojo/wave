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
        Schema::create('announcement_user', function (Blueprint $table) {
            $table->unsignedInteger('announcement_id');
            $table->unsignedBigInteger('user_id');

            // Indexes
            $table->index('announcement_id', 'announcement_user_announcement_id_index');
            $table->index('user_id', 'announcement_user_user_id_index');

            // Foreign keys
            $table->foreign('announcement_id')->references('id')->on('announcements')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('restrict');

            // Setting the primary keys
            $table->primary(['announcement_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_user');
    }
};
