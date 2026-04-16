<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Userservice;

class UserDeleteController extends Controller
{
    public function destroy($id, Userservice $service)
    {
       try{
            $service -> softDelete($id);
        return response()->json(['message' => 'Usuário deletado com sucesso.']);
        }
        catch(\Exception $e){
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }
    }
}
