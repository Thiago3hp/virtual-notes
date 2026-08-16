<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Services\ClienteService;

class ClienteCreateController extends Controller
{
    public function store(ClienteRequest $request, ClienteService $service)
    {
        $service->create($request->validated());

        return redirect()->route('clientes')->with('success', 'Cliente adicionado com sucesso!');
    }
}
