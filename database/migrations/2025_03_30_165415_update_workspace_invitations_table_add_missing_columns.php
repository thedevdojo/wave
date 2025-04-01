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
        Schema::table('workspace_invitations', function (Blueprint $table) {
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade')->after('id');
            $table->string('email')->after('workspace_id');
            $table->string('role')->default('member')->after('email');
            $table->string('token', 64)->unique()->after('role');
            $table->timestamp('accepted_at')->nullable()->after('token');
            $table->timestamp('expires_at')->nullable()->after('accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_invitations', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn([
                'workspace_id',
                'email',
                'role',
                'token',
                'accepted_at',
                'expires_at'
            ]);
        });
    }
};
