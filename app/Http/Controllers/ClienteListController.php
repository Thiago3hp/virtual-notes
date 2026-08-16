<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClienteResource;
use App\Services\ClienteService;
use Inertia\Inertia;

class ClienteListController extends Controller
{
    public function showList(ClienteService $service)
    {
        return Inertia::render('Clientes', [
            'cliente' => ClienteResource::collection($service->list()),
        ]);
    }
}
