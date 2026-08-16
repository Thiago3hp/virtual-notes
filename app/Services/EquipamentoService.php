<?php

namespace App\Services;

use App\Models\Equipamento;

class EquipamentoService
{
    public function list()
    {
        return Equipamento::with('chamado')->orderBy('nome')->get();
    }

    public function summary(): array
    {
        return [
            'total' => Equipamento::count(),
            'quantidade_total' => (int) Equipamento::sum('quantidade'),
            'vinculados_a_os' => Equipamento::whereNotNull('chamado_id')->count(),
        ];
    }

    public function create(array $dados): Equipamento
    {
        return Equipamento::create($dados);
    }

    public function update(int $id, array $dados): Equipamento
    {
        $equipamento = Equipamento::findOrFail($id);
        $equipamento->update($dados);

        return $equipamento;
    }

    public function delete(int $id): void
    {
        Equipamento::findOrFail($id)->delete();
    }
}
