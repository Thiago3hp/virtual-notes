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
        return redirect ()->back()->with('success', 'Usuário deletado com sucesso!');
        }
        catch(\Exception $e){
            return redirect ()->back()->with('error', 'Usuário não encontrado.');
        }
    }
}
