<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $creating = $this->isMethod('post');

        return [
            'nome' => $creating ? 'required|string|max:100' : 'sometimes|string|max:100',
            'descricao' => 'nullable|string',
            'quantidade' => 'sometimes|integer|min:0',
            'chamado_id' => 'nullable|integer|exists:chamados,id',
        ];
    }
}
