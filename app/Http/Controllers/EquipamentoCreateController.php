<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipamentoRequest;
use App\Services\EquipamentoService;

class EquipamentoCreateController extends Controller
{
    public function store(EquipamentoRequest $request, EquipamentoService $service)
    {
        $service->create($request->validated());

        return redirect()->route('equipamentos')->with('success', 'Equipamento adicionado com sucesso!');
    }
}
