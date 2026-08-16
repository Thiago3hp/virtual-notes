<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChamadoResource;
use App\Http\Resources\EquipamentoResource;
use App\Services\ChamadoService;
use App\Services\EquipamentoService;
use Inertia\Inertia;

class EquipamentoListController extends Controller
{
    public function showList(EquipamentoService $service, ChamadoService $chamadoService)
    {
        return Inertia::render('Equipamentos', [
            'equipamento' => EquipamentoResource::collection($service->list()),
            'chamado' => ChamadoResource::collection($chamadoService->list()),
        ]);
    }
}
