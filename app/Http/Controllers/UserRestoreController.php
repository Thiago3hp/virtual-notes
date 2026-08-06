<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class UserRestoreController extends Controller
{
    public function restore($id, UserService $service)
    {
        try {
            $service->restoreUser($id);

            return redirect()->back()->with('success', 'Usuário restaurado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }
    }
}
