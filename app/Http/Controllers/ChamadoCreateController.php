<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadoRequest;
use App\Mediators\ChamadoMediator;

class ChamadoCreateController extends Controller
{
    public function store(ChamadoRequest $request, ChamadoMediator $mediator)
    {
        $mediator->create($request->validated());

        return redirect()->route('chamadoshome')->with('success', 'Chamado criado com sucesso!');
    }
}
