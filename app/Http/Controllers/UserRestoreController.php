<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Userservice;

class UserRestoreController extends Controller
{
    public function restore($id, Userservice $service)
    {
        try {
            $service -> restore($id);
            return response()->json(['message' => 'Usuário restaurado com sucesso.']);
        } 
        catch (\Exception $e){
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }
    }
}
