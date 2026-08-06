<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mediators\ChamadoMediator;

class ChamadoCloseController extends Controller
{
    public function close($id, Request $request, ChamadoMediator $mediator)
    {
        try {
            $mediator->close($id, $request->only(['tecnico_nome', 'laudo_tecnico']));

            return redirect()->back()->with('success', 'Chamado encerrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível encerrar o chamado.');
        }
    }
}
