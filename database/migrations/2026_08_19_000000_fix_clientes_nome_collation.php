<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * chamados.setor foi criado pela conexão do bot (mysql2, sem charset
 * explícito), que pegou o padrão do MySQL 8 (utf8mb4_0900_ai_ci).
 * clientes.nome foi criado pela migration do Laravel, que usa o padrão
 * histórico do framework (utf8mb4_unicode_ci). Comparar as duas colunas
 * diretamente (`clientes.nome = chamados.setor`, usado na relação
 * Cliente::chamados()) quebra com "Illegal mix of collations" porque o
 * MySQL não compara strings de collations diferentes com '=' sem ajuda.
 *
 * Alinha clientes.nome pra usar a mesma collation de chamados.setor.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE clientes MODIFY nome VARCHAR(100) COLLATE utf8mb4_0900_ai_ci NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE clientes MODIFY nome VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL');
    }
};
