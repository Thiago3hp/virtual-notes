<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipamentoRequest;
use App\Services\EquipamentoService;

class EquipamentoUpdateController extends Controller
{
    public function update($id, EquipamentoRequest $request, EquipamentoService $service)
    {
        try {
            $service->update($id, $request->validated());

            return redirect()->route('equipamentos')->with('success', 'Equipamento atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Equipamento não encontrado.');
        }
    }
}
