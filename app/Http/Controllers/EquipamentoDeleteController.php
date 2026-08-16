<?php

namespace App\Http\Controllers;

use App\Services\EquipamentoService;

class EquipamentoDeleteController extends Controller
{
    public function destroy($id, EquipamentoService $service)
    {
        try {
            $service->delete($id);

            return redirect()->back()->with('success', 'Equipamento removido com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Equipamento não encontrado.');
        }
    }
}
