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
        Schema::create('translations', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing UNSIGNED INTEGER (primary key)
            $table->string('table_name', 191); // VARCHAR equivalent column for the table name
            $table->string('column_name', 191); // VARCHAR equivalent column for the column name
            $table->unsignedInteger('foreign_key'); // UNSIGNED INTEGER for the foreign key
            $table->string('locale', 191); // VARCHAR equivalent column for the locale
            $table->text('value'); // TEXT column for the translation value
            $table->timestamps(); // Adds created_at and updated_at columns

            // Unique index for combination of table_name, column_name, foreign_key, and locale to ensure unique translations
            $table->unique(['table_name', 'column_name', 'foreign_key', 'locale'], 'translations_table_column_foreign_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
