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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Using UUID for the primary key
            $table->string('type'); // Type of the notification
            $table->morphs('notifiable'); // Polymorphic relation columns (notifiable_id and notifiable_type)
            $table->text('data'); // Data column to store the notification's payload
            $table->timestamp('read_at')->nullable(); // Nullable timestamp for when the notification is read
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
