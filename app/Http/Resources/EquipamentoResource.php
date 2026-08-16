<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipamentoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'quantidade' => $this->quantidade,
            'chamado_id' => $this->chamado_id,
            'chamado' => $this->whenLoaded('chamado', fn () => [
                'id' => $this->chamado->id,
                'problema' => $this->chamado->problema,
            ]),
        ];
    }
}
