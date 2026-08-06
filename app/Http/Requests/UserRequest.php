<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $userId = $this->route('id');

        return auth()->check() && (is_null($userId) || auth()->id() == $userId);
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name' => 'string|required|min:5|max:80',
            'email' => ['string', 'required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'email_verified_at' => 'nullable|date',
            'password' => 'string|required|min:8|max:20',
        ];
    }
}
