<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * numero_tecnico: uma vez que a conta verifica o e-mail pela primeira
 * vez, o número de técnico informado no cadastro fica permanentemente
 * vinculado a ELA (índice único). Isso impede que duas contas diferentes
 * usem o mesmo número autorizado.
 *
 * verification_code / verification_code_expires_at: código de 6 dígitos
 * enviado por e-mail, temporário -- limpo assim que a verificação
 * acontece com sucesso.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('numero_tecnico', 20)->nullable()->unique()->after('email');
            $table->string('verification_code', 6)->nullable()->after('email_verified_at');
            $table->timestamp('verification_code_expires_at')->nullable()->after('verification_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['numero_tecnico', 'verification_code', 'verification_code_expires_at']);
        });
    }
};
