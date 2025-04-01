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
        Schema::table('generated_posts', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('workspace_id')->nullable()->constrained()->onDelete('cascade')->after('user_id');
            $table->text('content')->after('workspace_id');
            $table->string('topic')->after('content');
            $table->string('tone')->after('topic');
            $table->boolean('has_emoji')->default(false)->after('tone');
            $table->boolean('has_hashtags')->default(false)->after('has_emoji');
            $table->boolean('is_longform')->default(false)->after('has_hashtags');
            $table->boolean('posted_to_x')->default(false)->after('is_longform');
            $table->string('x_post_id')->nullable()->after('posted_to_x');
            $table->json('settings')->nullable()->after('x_post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generated_posts', function (Blueprint $table) {
            $table->dropColumn([
                'user_id',
                'workspace_id',
                'content',
                'topic',
                'tone',
                'has_emoji',
                'has_hashtags',
                'is_longform',
                'posted_to_x',
                'x_post_id',
                'settings'
            ]);
        });
    }
};
