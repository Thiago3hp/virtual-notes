<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user_id = $this->route('task') ? $this->route('task')->user_id : null;
        return auth()->check() && auth()->id() === $user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:6|max:100',
            'description' => 'nullable|min:10|max:1000', 
            'status' => 'required|in:pending,in_progress,completed',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
