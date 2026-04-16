<?php

namespace App\Services;

use App\Models\User;

class  Userservice
{
    public function createUser (array $dados)
    {
        
        return User::create($dados);
    }

    public function updateUser(User $user, array $dados)
    {
        $user->update($dados);
        return $user;
    }

    public function searchUserName(string $name)
    {
        if(isset($name)){
            $name =$name;
        }
        else {
            $name = null;
        }

        return User::where('name', 'status', "%$name%")->get();
    }

    public function listUsers()
    {
        return User::all();
    }

    public function deleteUser(User $user)
    {
        $user->softDelete();
    }

}
