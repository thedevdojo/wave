<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $statuses = [
        'posts' => [['PUBLISHED', 'DRAFT', 'PENDING'], 'DRAFT'],
        'pages' => [['ACTIVE', 'INACTIVE'], 'INACTIVE'],
    ];

    /**
     * Keep posts and pages when their author is deleted.
     */
    public function up(): void
    {
        $this->alterAuthor(nullable: true);
    }

    public function down(): void
    {
        $this->alterAuthor(nullable: false);
    }

    private function alterAuthor(bool $nullable): void
    {
        foreach ($this->statuses as $name => [$statuses, $default]) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropForeign(['author_id']);
            });

            Schema::table($name, function (Blueprint $table) use ($nullable, $statuses, $default) {
                $table->unsignedBigInteger('author_id')->nullable($nullable)->change();

                $foreign = $table->foreign('author_id')->references('id')->on('users');
                if ($nullable) {
                    $foreign->nullOnDelete();
                }

                // SQLite rebuilds the table and would drop the enum check otherwise.
                if (Schema::getConnection()->getDriverName() === 'sqlite') {
                    $table->enum('status', $statuses)->default($default)->change();
                }
            });
        }
    }
};
