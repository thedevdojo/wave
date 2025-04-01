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
        Schema::table('user_settings', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->after('id');
            $table->foreignId('workspace_id')->nullable()->constrained()->after('user_id');
            $table->string('key')->after('workspace_id');
            $table->json('value')->nullable()->after('key');
            
            // Add a unique constraint to prevent duplicate settings
            $table->unique(['user_id', 'workspace_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['workspace_id']);
            $table->dropUnique(['user_id', 'workspace_id', 'key']);
            $table->dropColumn([
                'user_id',
                'workspace_id',
                'key',
                'value'
            ]);
        });
    }
};
