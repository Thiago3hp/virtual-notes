<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Antes da correção em ChamadoService::update(), marcar um chamado como
 * "fechado" direto no diálogo de editar (em vez de usar o fluxo dedicado
 * de fechar) não carimbava fechado_em -- então esses chamados nunca
 * apareciam no gráfico de "concluídos por mês", que filtra por essa data.
 * Esse backfill corrige os que já ficaram nesse estado. A tabela chamados
 * não tem updated_at (schema do bot), então usamos a data de hoje como
 * aproximação -- não tem como recuperar a data real de fechamento.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('chamados')
            ->where('status', 'fechado')
            ->whereNull('fechado_em')
            ->update(['fechado_em' => now()]);
    }

    public function down(): void
    {
        // Não reversível com segurança (não dá pra saber quais linhas
        // foram tocadas por essa migration especificamente).
    }
};
