<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Codes are now stored encrypted (not hashed) so a resend can carry the
     * same still-valid code instead of silently invalidating every email
     * already in the user's inbox. Outstanding hashed rows can't be
     * decrypted; verifyEmailWithCode() treats them as expired, which
     * rotates in a fresh code on the next send — they age out within the
     * 15-minute expiry window anyway.
     */
    public function up(): void
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {
            $table->renameColumn('code_hash', 'code');
        });

        Schema::table('email_verification_codes', function (Blueprint $table) {
            // Encrypted payloads outgrow the default 255.
            $table->text('code')->change();
        });
    }

    public function down(): void
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {
            $table->renameColumn('code', 'code_hash');
        });

        Schema::table('email_verification_codes', function (Blueprint $table) {
            $table->string('code_hash')->change();
        });
    }
};
