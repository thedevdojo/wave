<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pagebuilder.storage.database.prefix') . 'uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_id', 50)->unique();
            $table->string('original_file', 512);
            $table->string('mime_type', 50);
            $table->string('server_file', 512)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('pagebuilder.storage.database.prefix') . 'uploads');
    }
}
