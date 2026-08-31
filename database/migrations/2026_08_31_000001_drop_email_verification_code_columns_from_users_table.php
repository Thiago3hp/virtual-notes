<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * verification_code / verification_code_expires_at eram usadas só pelo
 * fluxo de verificação por e-mail, retirado em 31/08/2026 (ver
 * routes/web.php, CreateNewUser e EnsureNumeroIsVerified). Nada no
 * código escreve ou lê essas colunas hoje.
 *
 * Mantive email_verified_at de propósito -- é uma coluna padrão do
 * Laravel, usada por outras partes do framework (Fortify, etc.), então é
 * mais seguro deixá-la aí sem uso do que removê-la.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['verification_code', 'verification_code_expires_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('verification_code', 6)->nullable()->after('email_verified_at');
            $table->timestamp('verification_code_expires_at')->nullable()->after('verification_code');
        });
    }
};
