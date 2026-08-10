<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Covers both creating a chamado manually (POST, técnico opening one that
 * didn't come from WhatsApp) and editing one (PUT). solicitante_jid stays
 * off-limits either way -- that's strictly a WhatsApp identifier.
 */
class ChamadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $creating = $this->isMethod('post');

        return [
            'solicitante_nome' => $creating ? 'required|string|max:255' : 'sometimes|nullable|string|max:255',
            'solicitante_numero' => $creating ? 'required|string|max:20' : 'sometimes|string|max:20',
            'numero_adicional' => 'nullable|string|max:20',
            'setor' => $creating ? 'required|string|max:100' : 'sometimes|string|max:100',
            'problema' => $creating ? 'required|string|max:255' : 'sometimes|string|max:255',
            'descricao' => 'nullable|string',
            'sala' => 'nullable|string|max:255',
            'status' => 'sometimes|string|in:aberto,em_andamento,fechado',
            'prazo' => 'nullable|date',
            'prioridade' => 'sometimes|string|in:Baixa,Normal,Alta,Urgente',
            'data_coleta' => 'nullable|date',
            'tecnico_nome' => 'nullable|string|max:255',
            'laudo_tecnico' => 'nullable|string',
        ];
    }
}
