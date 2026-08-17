<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
 *
 * IMPORTANTE: nem todo MySQL gerenciado (planos compartilhados, algumas
 * configurações de host) concede o privilégio TRIGGER pro usuário da
 * aplicação. Sem o try/catch abaixo, uma falha aqui abortava o lote de
 * migrations inteiro -- e todas as migrations seguintes (equipamentos,
 * chamado_id, o backfill de fechado_em) nunca chegavam a rodar. Agora,
 * se o trigger não puder ser criado, a migration segue em frente e só
 * registra um aviso no log.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Backfill: puxa os setores que já existem nos chamados atuais
        // (inclusive os que o bot já inseriu antes dessa migration existir).
        // Roda só uma vez, aqui na migration.
        try {
            DB::unprepared(<<<'SQL'
                INSERT IGNORE INTO clientes (nome, created_at, updated_at)
                SELECT DISTINCT setor, NOW(), NOW()
                FROM chamados
                WHERE setor IS NOT NULL AND setor <> ''
            SQL);
        } catch (\Throwable $e) {
            Log::warning('Backfill de clientes a partir de chamados falhou: '.$e->getMessage());
        }

        // Dali em diante, qualquer chamado novo (bot ou painel) sincroniza sozinho.
        try {
            DB::unprepared(<<<'SQL'
                CREATE TRIGGER sync_cliente_after_chamado_insert
                AFTER INSERT ON chamados
                FOR EACH ROW
                INSERT IGNORE INTO clientes (nome, created_at, updated_at)
                VALUES (NEW.setor, NOW(), NOW())
            SQL);
        } catch (\Throwable $e) {
            Log::warning(
                'Não foi possível criar o trigger sync_cliente_after_chamado_insert '.
                '(provavelmente falta o privilégio TRIGGER pro usuário do banco neste ambiente). '.
                'Chamados novos criados pelo bot não vão sincronizar clientes automaticamente '.
                'até isso ser resolvido manualmente. Erro original: '.$e->getMessage()
            );
        }
    }

    public function down(): void
    {
        try {
            DB::unprepared('DROP TRIGGER IF EXISTS sync_cliente_after_chamado_insert');
        } catch (\Throwable $e) {
            Log::warning('Não foi possível remover o trigger sync_cliente_after_chamado_insert: '.$e->getMessage());
        }
    }
};
