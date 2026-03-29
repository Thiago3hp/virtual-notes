<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nome' => 'required |min:3'
        ];
    }
}
?>
