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
        Schema::table('posts', function (Blueprint $table) {
            // Add fields for AI-generated posts
            $table->text('content')->nullable()->after('body');
            $table->string('topic')->nullable()->after('content');
            $table->string('tone')->nullable()->after('topic');
            $table->boolean('has_emoji')->default(false)->after('tone');
            $table->boolean('has_hashtags')->default(false)->after('has_emoji');
            $table->boolean('is_longform')->default(false)->after('has_hashtags');
            $table->boolean('posted_to_x')->default(false)->after('is_longform');
            $table->string('x_post_id')->nullable()->after('posted_to_x');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'content',
                'topic',
                'tone',
                'has_emoji',
                'has_hashtags',
                'is_longform',
                'posted_to_x',
                'x_post_id'
            ]);
        });
    }
};
