<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadoRequest;
use App\Mediators\ChamadoMediator;

class ChamadoUpdateController extends Controller
{
    public function update($id, ChamadoRequest $request, ChamadoMediator $mediator)
    {
        try {
            $mediator->update($id, $request->validated());

            return redirect()->route('chamadoshome')->with('success', 'Chamado atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Chamado não encontrado.');
        }
    }
}
