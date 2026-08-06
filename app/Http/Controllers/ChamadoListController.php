<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadoRequest;
use App\Http\Resources\ChamadoResource;
use App\Services\ChamadoService;
use Inertia\Inertia;

class ChamadoListController extends Controller
{
    public function showList(ChamadoService $service)
    {
        return Inertia::render('ChamadosHome', [
            'chamado' => ChamadoResource::collection($service->list()),
        ]);
    }

    public function showListId($id, ChamadoService $service)
    {
        try {
            return new ChamadoResource($service->find($id));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Chamado não encontrado.');
        }
    }

    public function searchByStatus(ChamadoRequest $request, ChamadoService $service)
    {
        return ChamadoResource::collection($service->searchByStatus($request->input('status')));
    }

    public function searchBySetor(ChamadoRequest $request, ChamadoService $service)
    {
        return ChamadoResource::collection($service->searchBySetor($request->input('setor')));
    }
}
