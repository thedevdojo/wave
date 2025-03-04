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
            // Check if the columns exist before trying to drop them
            if (Schema::hasColumn('posts', 'content')) {
                $table->dropColumn('content');
            }
            if (Schema::hasColumn('posts', 'topic')) {
                $table->dropColumn('topic');
            }
            if (Schema::hasColumn('posts', 'tone')) {
                $table->dropColumn('tone');
            }
            if (Schema::hasColumn('posts', 'has_emoji')) {
                $table->dropColumn('has_emoji');
            }
            if (Schema::hasColumn('posts', 'has_hashtags')) {
                $table->dropColumn('has_hashtags');
            }
            if (Schema::hasColumn('posts', 'is_longform')) {
                $table->dropColumn('is_longform');
            }
            if (Schema::hasColumn('posts', 'posted_to_x')) {
                $table->dropColumn('posted_to_x');
            }
            if (Schema::hasColumn('posts', 'x_post_id')) {
                $table->dropColumn('x_post_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Note: We don't need to add these columns back in the down method
     * as they are now part of the generated_posts table.
     */
    public function down(): void
    {
        // No need to add these columns back
    }
};
