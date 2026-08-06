<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    public function store(UserRequest $request, UserService $service)
    {
        $user = $service->createUser($request->validated());

        return new UserResource($user);
    }
}
