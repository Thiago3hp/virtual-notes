<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'chamados_count' => $this->chamados_count ?? 0,
            'abertos_count' => $this->abertos_count ?? 0,
            'em_andamento_count' => $this->em_andamento_count ?? 0,
            'fechados_count' => $this->fechados_count ?? 0,
        ];
    }
}
