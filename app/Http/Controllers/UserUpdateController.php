<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\Userservice;


class UserUpdateController extends Controller
{
    public function update($id, UserRequest $request, Userservice $service)
    {
        try {
            $usuario = $service -> atualizar($id, $request -> validated());
            return new UserResource($usuario);
        } 
        catch (\Exception $e){
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }
    }
}
