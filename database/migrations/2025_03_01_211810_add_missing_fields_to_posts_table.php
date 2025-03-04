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
            // Add default values for required fields if they don't exist
            if (!Schema::hasColumn('posts', 'title')) {
                $table->string('title', 191)->default('Generated Post')->after('author_id');
            }
            
            if (!Schema::hasColumn('posts', 'body')) {
                $table->text('body')->default('')->after('title');
            }
            
            if (!Schema::hasColumn('posts', 'slug')) {
                $table->string('slug', 191)->unique()->default('generated-post-' . time())->after('body');
            }
            
            if (!Schema::hasColumn('posts', 'status')) {
                $table->enum('status', ['PUBLISHED', 'DRAFT', 'PENDING'])->default('PUBLISHED')->after('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to remove these columns in the down method
        // as they are core to the Wave package
    }
};
