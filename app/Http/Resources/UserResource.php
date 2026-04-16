<?php

namespace App\Http\Resources;

use Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this -> id,
            'nome' => $this -> name,
            'email' => $this -> email,
            'email_verified_at' => $this -> email_verified_at?->toDateTimeString(),
            'created_at' => $this -> created_at?->toDateTimeString(),
            'updated_at' => $this -> updated_at?->toDateTimeString(),
        ];
    }
}
