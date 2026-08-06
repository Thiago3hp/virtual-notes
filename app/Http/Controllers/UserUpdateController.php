<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserUpdateController extends Controller
{
    public function update($id, UserRequest $request, UserService $service)
    {
        try {
            $user = $service->updateUser($id, $request->validated());

            return new UserResource($user);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }
    }
}
