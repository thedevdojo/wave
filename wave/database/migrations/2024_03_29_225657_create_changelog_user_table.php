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
        Schema::create('changelog_user', function (Blueprint $table) {
            $table->unsignedInteger('changelog_id');
            $table->unsignedBigInteger('user_id');

            // Indexes
            $table->index('changelog_id', 'changelog_user_changelog_id_index');
            $table->index('user_id', 'changelog_user_user_id_index');

            // Foreign keys
            $table->foreign('changelog_id')->references('id')->on('changelogs')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('restrict');

            // Setting the primary keys
            $table->primary(['changelog_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changelog_user');
    }
};
