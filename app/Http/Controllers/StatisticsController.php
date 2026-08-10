<?php

namespace App\Http\Controllers;

use App\Services\ChamadoService;
use Inertia\Inertia;

class StatisticsController extends Controller
{
    public function index(ChamadoService $service)
    {
        return Inertia::render('Statistics', [
            'concluidosPorMes' => $service->completedByMonth(6),
        ]);
    }
}
