<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('image')->nullable();
            $table->string('page_heading')->nullable();
            $table->text('page_description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'meta_description', 'meta_keywords', 'image', 'page_heading', 'page_description']);
        });
    }
};
