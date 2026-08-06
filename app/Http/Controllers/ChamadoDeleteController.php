<?php

namespace App\Http\Controllers;

use App\Services\ChamadoService;

class ChamadoDeleteController extends Controller
{
    public function destroy($id, ChamadoService $service)
    {
        try {
            $service->delete($id);

            return redirect()->back()->with('success', 'Chamado excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Chamado não encontrado.');
        }
    }
}
