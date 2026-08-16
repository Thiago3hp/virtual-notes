<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'nome' => ['required', 'string', 'max:100', Rule::unique('clientes', 'nome')->ignore($id)],
        ];
    }
}
