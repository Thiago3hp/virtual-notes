<?php

namespace App\Http\Controllers;

use App\Services\ChamadoService;
use App\Services\ClienteService;
use App\Services\EquipamentoService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(
        ChamadoService $chamadoService,
        ClienteService $clienteService,
        EquipamentoService $equipamentoService,
    ) {
        return Inertia::render('Dashboard', [
            'chamadosSummary' => $chamadoService->summary(),
            'clientesSummary' => $clienteService->summary(),
            'clienteTop' => $clienteService->top(),
            'equipamentosSummary' => $equipamentoService->summary(),
            'concluidosPorMes' => $chamadoService->completedByMonth(6),
        ]);
    }
}
