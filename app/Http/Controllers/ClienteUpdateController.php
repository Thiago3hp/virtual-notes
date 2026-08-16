<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Services\ClienteService;

class ClienteUpdateController extends Controller
{
    public function update($id, ClienteRequest $request, ClienteService $service)
    {
        try {
            $service->update($id, $request->validated());

            return redirect()->route('clientes')->with('success', 'Cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cliente não encontrado.');
        }
    }
}
