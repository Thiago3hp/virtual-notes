<?php

namespace App\Console\Commands;

use App\Models\Chamado;
use App\Models\Cliente;
use App\Models\Equipamento;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Importa ordens de serviço específicas de um export SQLite do OrbiTask
 * pra dentro de "chamados", sem duplicar e sem tocar em nada que já
 * existe. A proteção contra duplicata é por design: cada linha importada
 * grava uma origem_referencia única (ex: "orbitask:ordem:5"); rodar o
 * mesmo import de novo simplesmente pula quem já tem essa referência.
 */
class ImportarOrbitask extends Command
{
    protected $signature = 'chamados:importar-orbitask
        {arquivo : Caminho para o arquivo .db (SQLite) do OrbiTask}
        {--ids= : IDs das ordens_servico a importar, separados por vírgula (ex: 1,2,3). Sem isso, importa todas.}
        {--dry-run : Mostra o que seria importado, sem gravar nada}';

    protected $description = 'Importa ordens de serviço selecionadas de um export do OrbiTask, sem duplicar nem alterar dados existentes';

    private const STATUS_MAP = [
        'aberta' => 'aberto',
        'em_andamento' => 'em_andamento',
        'concluida' => 'fechado',
    ];

    private const PRIORIDADE_MAP = [
        'baixa' => 'Baixa',
        'normal' => 'Normal',
        'alta' => 'Alta',
        'urgente' => 'Urgente',
    ];

    public function handle(): int
    {
        $caminho = $this->argument('arquivo');

        if (! file_exists($caminho)) {
            $this->error("Arquivo não encontrado: {$caminho}");

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');

        $idsFiltro = null;
        if ($this->option('ids')) {
            $idsFiltro = array_map('intval', explode(',', (string) $this->option('ids')));
        }

        // Conexão extra só de leitura pro SQLite -- não mexe na conexão
        // principal (MySQL) do app.
        Config::set('database.connections.orbitask_import', [
            'driver' => 'sqlite',
            'database' => $caminho,
            'prefix' => '',
        ]);
        $origem = DB::connection('orbitask_import');

        $clientesOrigem = $origem->table('clientes')->get()->keyBy('id');
        $usuariosOrigem = $origem->table('usuarios')->get()->keyBy('id');
        $equipamentosOrigem = $origem->table('equipamentos')->get()->groupBy('ordem_id');

        $query = $origem->table('ordens_servico')->orderBy('id');
        if ($idsFiltro) {
            $query->whereIn('id', $idsFiltro);
        }
        $ordens = $query->get();

        if ($ordens->isEmpty()) {
            $this->warn('Nenhuma ordem de serviço encontrada com esse filtro.');

            return self::SUCCESS;
        }

        $this->info(($dryRun ? '[DRY-RUN] ' : '')."Processando {$ordens->count()} ordem(ns) de serviço...");
        $this->newLine();

        $importados = 0;
        $ignorados = 0;
        $equipamentosImportados = 0;

        DB::beginTransaction();

        try {
            foreach ($ordens as $ordem) {
                $referencia = "orbitask:ordem:{$ordem->id}";

                if (Chamado::where('origem_referencia', $referencia)->exists()) {
                    $this->line("  #{$ordem->id} \"{$ordem->titulo}\" -- já importado antes, pulando.");
                    $ignorados++;

                    continue;
                }

                $cliente = $clientesOrigem->get($ordem->cliente_id);
                $tecnico = $usuariosOrigem->get($ordem->tecnico_id);
                $nomeCliente = $cliente->nome ?? 'Cliente não identificado';

                $descricao = trim((string) $ordem->descricao);
                $notas = [];
                if ((float) $ordem->valor_servico > 0) {
                    $notas[] = 'Valor serviço: R$ '.number_format((float) $ordem->valor_servico, 2, ',', '.');
                }
                if ((float) $ordem->valor_pecas > 0) {
                    $notas[] = 'Valor peças: R$ '.number_format((float) $ordem->valor_pecas, 2, ',', '.');
                }
                if ($ordem->status_pagamento && $ordem->status_pagamento !== 'pendente') {
                    $notas[] = 'Pagamento: '.$ordem->status_pagamento;
                }
                if ($notas) {
                    $descricao = trim($descricao."\n\n[Importado do OrbiTask] ".implode(' · ', $notas));
                }

                $status = self::STATUS_MAP[$ordem->status] ?? 'aberto';

                $this->line("  #{$ordem->id} \"{$ordem->titulo}\" ({$nomeCliente}) -- importando...");

                if (! $dryRun) {
                    Cliente::firstOrCreate(['nome' => $nomeCliente]);

                    $chamado = new Chamado([
                        'solicitante_nome' => $nomeCliente,
                        'solicitante_numero' => $cliente->telefone ?: 'importado-orbitask',
                        'setor' => $nomeCliente,
                        'problema' => $ordem->titulo,
                        'descricao' => $descricao ?: null,
                        'status' => $status,
                        'prioridade' => self::PRIORIDADE_MAP[$ordem->prioridade] ?? 'Normal',
                        'prazo' => $ordem->prazo ?: null,
                        'tecnico_nome' => $tecnico->nome ?? null,
                        'fechado_em' => $status === 'fechado' ? $ordem->atualizado_em : null,
                        'origem_referencia' => $referencia,
                    ]);

                    // Preserva a data de criação original em vez de carimbar "agora".
                    $chamado->timestamps = false;
                    $chamado->criado_em = $ordem->criado_em;
                    $chamado->save();

                    foreach ($equipamentosOrigem->get($ordem->id, collect()) as $equip) {
                        $partes = array_filter([
                            $equip->tipo, $equip->marca, $equip->modelo,
                            $equip->numero_serie, $equip->descricao,
                        ]);

                        Equipamento::create([
                            'nome' => $equip->nome,
                            'descricao' => $partes ? implode(' · ', $partes) : null,
                            'quantidade' => 1,
                            'chamado_id' => $chamado->id,
                        ]);

                        $equipamentosImportados++;
                    }
                } else {
                    $equipamentosImportados += $equipamentosOrigem->get($ordem->id, collect())->count();
                }

                $importados++;
            }

            if ($dryRun) {
                DB::rollBack();
            } else {
                DB::commit();
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Falha na importação, nada foi gravado: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info(($dryRun ? '[DRY-RUN] Simulação concluída (nada foi gravado).' : 'Importação concluída.'));
        $this->line("  Chamados importados: {$importados}");
        $this->line("  Chamados ignorados (já importados antes): {$ignorados}");
        $this->line("  Equipamentos importados: {$equipamentosImportados}");

        return self::SUCCESS;
    }
}
