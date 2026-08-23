<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rastreia de onde um chamado veio quando importado de um sistema externo
 * (ex: "orbitask:ordem:5"). Nula pra tudo que já existia antes (bot ou
 * criado manualmente no painel) -- só populada por comandos de import.
 *
 * O índice único é o que garante, na prática, que rodar o mesmo import
 * duas vezes não duplica nada: a segunda tentativa de inserir a mesma
 * origem_referencia simplesmente é pulada.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chamados', function (Blueprint $table) {
            $table->string('origem_referencia', 100)->nullable()->unique()->after('solicitante_jid');
        });
    }

    public function down(): void
    {
        Schema::table('chamados', function (Blueprint $table) {
            $table->dropColumn('origem_referencia');
        });
    }
};
