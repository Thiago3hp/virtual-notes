<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Sincroniza "clientes" automaticamente sempre que um chamado é inserido
 * na tabela compartilhada -- seja pelo bot do WhatsApp (INSERT via SQL
 * puro, fora do Eloquent) ou pelo painel (ChamadoCreateController).
 *
 * Um Observer do Laravel NÃO resolveria isso sozinho: só dispara para
 * inserts feitos através do Eloquent, e o bot nunca passa por ali. Um
 * trigger no nível do banco cobre os dois casos, porque roda em cima de
 * qualquer INSERT físico na tabela, não importa quem o fez.
 *
 * INSERT IGNORE + índice único em clientes.nome garante que um setor que
 * já existe simplesmente é ignorado, sem duplicar nem dar erro.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Backfill: puxa os setores que já existem nos chamados atuais
        // (inclusive os que o bot já inseriu antes dessa migration existir).
        // Roda só uma vez, aqui na migration.
        DB::unprepared(<<<'SQL'
            INSERT IGNORE INTO clientes (nome, created_at, updated_at)
            SELECT DISTINCT setor, NOW(), NOW()
            FROM chamados
            WHERE setor IS NOT NULL AND setor <> ''
        SQL);

        // Dali em diante, qualquer chamado novo (bot ou painel) sincroniza sozinho.
        DB::unprepared(<<<'SQL'
            CREATE TRIGGER sync_cliente_after_chamado_insert
            AFTER INSERT ON chamados
            FOR EACH ROW
            INSERT IGNORE INTO clientes (nome, created_at, updated_at)
            VALUES (NEW.setor, NOW(), NOW())
        SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS sync_cliente_after_chamado_insert');
    }
};
