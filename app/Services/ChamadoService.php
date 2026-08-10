<?php

namespace App\Services;

use App\Models\Chamado;

class ChamadoService
{
    /**
     * Manual creation from the dashboard (a técnico opening a chamado that
     * didn't come in through WhatsApp). Bot-created chamados never go
     * through this -- they're inserted directly into MySQL.
     */
    public function create(array $dados): Chamado
    {
        return Chamado::create($dados);
    }

    public function list()
    {
        return Chamado::orderByDesc('criado_em')->get();
    }

    public function find(int $id): Chamado
    {
        return Chamado::findOrFail($id);
    }

    public function searchByStatus(?string $status)
    {
        return Chamado::where('status', $status)->orderByDesc('criado_em')->get();
    }

    public function searchBySetor(?string $setor)
    {
        return Chamado::where('setor', 'LIKE', '%'.$setor.'%')->orderByDesc('criado_em')->get();
    }

    /**
     * Chamados fechados, agrupados por mês de fechamento (fechado_em).
     * Retorna os últimos $months meses, incluindo meses sem nenhum
     * chamado concluído (total 0), pra o gráfico não ficar com buracos.
     */
    public function completedByMonth(int $months = 6): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);

        $rows = Chamado::where('status', 'fechado')
            ->whereNotNull('fechado_em')
            ->where('fechado_em', '>=', $start)
            ->selectRaw("DATE_FORMAT(fechado_em, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];

        $result = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');

            $result[] = [
                'month' => $key,
                'label' => $meses[$date->month - 1].'/'.$date->format('y'),
                'total' => (int) ($rows[$key] ?? 0),
            ];
        }

        return $result;
    }

    /**
     * Dashboard-only fields the bot never touches: técnico assigned,
     * status, laudo, prazo, prioridade. Mirrors what the bot's own
     * "tecnico" flow does over WhatsApp (see closeTicketWithReport in
     * ticketService.js), but from the web.
     */
    public function update(int $id, array $dados): Chamado
    {
        $chamado = Chamado::findOrFail($id);
        $chamado->update($dados);

        return $chamado;
    }

    /**
     * Equivalent to the bot's closeTicketWithReport(): closes the ticket
     * and stamps fechado_em, same as WhatsApp does.
     */
    public function closeWithReport(int $id, string $tecnicoNome, string $laudo): Chamado
    {
        $chamado = Chamado::findOrFail($id);
        $chamado->update([
            'status' => 'fechado',
            'fechado_em' => now(),
            'tecnico_nome' => $tecnicoNome,
            'laudo_tecnico' => $laudo,
        ]);

        return $chamado;
    }

    /**
     * The bot's schema has no soft-delete column, so this is a hard
     * delete -- there's no "excluído" state in chamados, unlike the old
     * Task model.
     */
    public function delete(int $id): void
    {
        Chamado::findOrFail($id)->delete();
    }
}
