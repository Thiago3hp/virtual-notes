<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * O bot (T.I-Sesa-bot) agora tenta reenviar uma mensagem da fila
 * whatsapp_outbox algumas vezes antes de marcar como 'falhou' -- essa
 * coluna guarda quantas tentativas ja foram feitas, pra ele saber quando
 * parar (ver src/services/whatsappOutboxService.js e
 * src/bot/outboxPoller.js no bot).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_outbox', function (Blueprint $table) {
            $table->unsignedTinyInteger('tentativas')->default(0)->after('mensagem');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_outbox', function (Blueprint $table) {
            $table->dropColumn('tentativas');
        });
    }
};
