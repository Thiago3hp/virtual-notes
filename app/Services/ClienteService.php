<?php

namespace App\Services;

use App\Models\Cliente;

class ClienteService
{
    public function list()
    {
        return Cliente::withCount([
            'chamados',
            'chamados as abertos_count' => fn ($q) => $q->where('status', 'aberto'),
            'chamados as em_andamento_count' => fn ($q) => $q->where('status', 'em_andamento'),
            'chamados as fechados_count' => fn ($q) => $q->where('status', 'fechado'),
        ])->orderBy('nome')->get();
    }

    public function create(array $dados): Cliente
    {
        return Cliente::create($dados);
    }

    public function update(int $id, array $dados): Cliente
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($dados);

        return $cliente;
    }

    public function delete(int $id): void
    {
        Cliente::findOrFail($id)->delete();
    }

    public function summary(): array
    {
        return [
            'total' => Cliente::count(),
        ];
    }

    /**
     * Cliente com mais chamados registrados (qualquer status), pra dar
     * destaque no dashboard. Null se ainda não tem chamado nenhum.
     */
    public function top(): ?array
    {
        $top = Cliente::withCount('chamados')->orderByDesc('chamados_count')->first();

        if (! $top || $top->chamados_count === 0) {
            return null;
        }

        return ['nome' => $top->nome, 'total' => $top->chamados_count];
    }
}
