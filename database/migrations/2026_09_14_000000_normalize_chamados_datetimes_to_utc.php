<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * O bot do WhatsApp grava criado_em/fechado_em com o horário local de
 * Brasília (America/Sao_Paulo), mas o app.timezone da aplicação é UTC --
 * então o Carbon lê esses valores como se já fossem UTC. Até aqui isso
 * não dava problema porque o ChamadoResource devolvia a data crua, sem
 * nenhuma conversão de fuso.
 *
 * Agora que o ChamadoResource passou a exibir ->timezone('America/Sao_Paulo')
 * (convertendo de UTC para horário local), os chamados já existentes no
 * banco precisam ser corrigidos: como o valor gravado já é horário local
 * (e não UTC), subtraímos 3 horas para "torná-lo" UTC de verdade. Assim,
 * ao converter de volta pra America/Sao_Paulo na exibição, o horário
 * volta a bater com o que foi realmente registrado.
 *
 * Só afeta os chamados já criados até este ponto -- os novos, tanto os
 * gravados pelo bot (raw insert, horário local, igual antes) quanto os
 * gravados pela dashboard via now() (que já é UTC de verdade, pois
 * respeita app.timezone), não devem passar por esse ajuste de novo.
 * Por isso a migration roda uma única vez, sobre os dados existentes.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('chamados')->update([
            'criado_em' => DB::raw('DATE_SUB(criado_em, INTERVAL 3 HOUR)'),
        ]);

        DB::table('chamados')
            ->whereNotNull('fechado_em')
            ->update([
                'fechado_em' => DB::raw('DATE_SUB(fechado_em, INTERVAL 3 HOUR)'),
            ]);
    }

    public function down(): void
    {
        DB::table('chamados')->update([
            'criado_em' => DB::raw('DATE_ADD(criado_em, INTERVAL 3 HOUR)'),
        ]);

        DB::table('chamados')
            ->whereNotNull('fechado_em')
            ->update([
                'fechado_em' => DB::raw('DATE_ADD(fechado_em, INTERVAL 3 HOUR)'),
            ]);
    }
};
