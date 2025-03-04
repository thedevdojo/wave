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
        Schema::create('generated_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->string('topic');
            $table->string('tone')->nullable();
            $table->boolean('has_emoji')->default(false);
            $table->boolean('has_hashtags')->default(false);
            $table->boolean('is_longform')->default(false);
            $table->boolean('posted_to_x')->default(false);
            $table->string('x_post_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_posts');
    }
};
