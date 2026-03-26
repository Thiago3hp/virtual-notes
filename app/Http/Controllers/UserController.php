<?php

namespace App\Http\Controllers;

use App\Http\Request\UserRequest;
use App\Services\Userservice;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function store(UserRequest $request, Userservice $service)
    {
        $usuario = $service -> criar($request -> validated());

        return new UserResource($usuario);
    }
};
