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
        Schema::table('workspace_user', function (Blueprint $table) {
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('workspace_id');
            $table->string('role')->default('member')->after('user_id');
            
            // If created_at is missing, add it
            if (!Schema::hasColumn('workspace_user', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_user', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'workspace_id',
                'user_id',
                'role'
            ]);
        });
    }
};
