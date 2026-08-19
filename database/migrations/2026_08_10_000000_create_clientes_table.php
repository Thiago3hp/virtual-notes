<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cadastro dos clientes (setores atendidos pelo técnico). Não tem relação
 * de chave estrangeira com "chamados" -- a coluna chamados.setor é um
 * VARCHAR livre (parte do schema compartilhado com o bot do WhatsApp, que
 * nunca deve mudar), então essa tabela só serve pra alimentar o seletor no
 * painel e padronizar os nomes usados ali.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';

            $table->id();
            $table->string('nome', 100)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
