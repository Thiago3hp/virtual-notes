<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChamadoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'solicitante_nome' => $this->solicitante_nome,
            'setor' => $this->setor,
            'problema' => $this->problema,
            'descricao' => $this->descricao,
            'sala' => $this->sala,
            'solicitante_numero' => $this->solicitante_numero,
            'numero_adicional' => $this->numero_adicional,
            'status' => $this->status,
            'prazo' => $this->prazo?->toDateString(),
            'prioridade' => $this->prioridade,
            'data_coleta' => $this->data_coleta?->toDateString(),
            'tecnico_nome' => $this->tecnico_nome,
            'laudo_tecnico' => $this->laudo_tecnico,
            'avaliacao' => $this->avaliacao,
            'criado_em' => $this->criado_em?->toDateTimeString(),
            'fechado_em' => $this->fechado_em?->toDateTimeString(),
        ];
    }
}
