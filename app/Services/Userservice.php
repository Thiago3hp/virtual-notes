<?php

namespace App\Services;

use App\Models\User;

class  Userservice
{
    public function criar (array $dados)
    {
        return User::create($dados);
    }
}

?>