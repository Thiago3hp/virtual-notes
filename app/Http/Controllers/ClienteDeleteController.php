<?php

namespace App\Http\Controllers;

use App\Services\ClienteService;

class ClienteDeleteController extends Controller
{
    public function destroy($id, ClienteService $service)
    {
        try {
            $service->delete($id);

            return redirect()->back()->with('success', 'Cliente removido com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cliente não encontrado.');
        }
    }
}
