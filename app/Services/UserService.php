<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser(array $dados): User
    {
        return User::create($dados);
    }

    public function updateUser(int $id, array $dados): User
    {
        $user = User::findOrFail($id);
        $user->update($dados);

        return $user;
    }

    public function searchUserName(?string $name)
    {
        return User::where('name', 'LIKE', '%'.$name.'%')->get();
    }

    public function listUsers()
    {
        return User::all();
    }

    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function restoreUser(int $id): User
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return $user;
    }
}
