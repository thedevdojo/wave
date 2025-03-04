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
        Schema::create('inspiration_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable(); // Business, Technology, Lifestyle, etc.
            $table->text('description')->nullable();
            $table->boolean('is_trending')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // Create pivot table for inspirations and tags
        Schema::create('inspiration_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspiration_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('inspiration_tags')->onDelete('cascade');
            $table->timestamps();

            // Ensure an inspiration can't have the same tag twice
            $table->unique(['inspiration_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspiration_tag');
        Schema::dropIfExists('inspiration_tags');
    }
};
