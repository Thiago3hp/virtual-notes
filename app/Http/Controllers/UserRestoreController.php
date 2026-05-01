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
            return redirect ()->back()->with('success', 'Usuário restaurado com sucesso!');
        } 
        catch (\Exception $e){
            return redirect ()->back()->with('error', 'Usuário não encontrado.');
        }
    }
}
