<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Segunda verificação além do e-mail: confirmar que a pessoa realmente
 * tem acesso ao número de técnico informado, via código enviado por
 * WhatsApp. O Laravel não consegue mandar WhatsApp sozinho -- quem tem
 * essa capacidade é o bot (T.I-Sesa-bot, Baileys). Então, seguindo o
 * mesmo princípio já usado no resto do projeto (banco compartilhado, sem
 * API entre os dois serviços), o Laravel só GRAVA a mensagem numa fila
 * (whatsapp_outbox) e o bot fica responsável por ler e enviar de fato.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('numero_verification_code', 6)->nullable()->after('numero_tecnico');
            $table->timestamp('numero_verification_expires_at')->nullable()->after('numero_verification_code');
            $table->timestamp('numero_verified_at')->nullable()->after('numero_verification_expires_at');
        });

        Schema::create('whatsapp_outbox', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';

            $table->id();
            $table->string('numero', 20);
            $table->text('mensagem');
            // 'pendente' até o bot processar; ele marca 'enviado' (ou
            // 'falhou') depois de tentar. O Laravel nunca apaga a linha,
            // só lê o status pra decidir o que mostrar na tela.
            $table->enum('status', ['pendente', 'enviado', 'falhou'])->default('pendente');
            $table->timestamp('enviado_em')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_outbox');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['numero_verification_code', 'numero_verification_expires_at', 'numero_verified_at']);
        });
    }
};
