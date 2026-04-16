<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{

    public function authorize()
    {
        $user_id = $this->route('usuario') ? $this->route('usuario')->id : null;
        return auth()->check() && auth()->id() === $user_id;
    }

    public function rules()
    {
        return [
            'name' => 'string|required|min:10|max:80',
            'email' => 'string|required|email|unique:usuarios,email',
            'email_verified_at' => 'nullable|date',
            'password' => 'string|required|min:8|max:20',
        ];
    }
}
?>
