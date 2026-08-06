<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class UserDeleteController extends Controller
{
    public function destroy($id, UserService $service)
    {
        try {
            $service->deleteUser($id);

            return redirect()->back()->with('success', 'Usuário deletado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }
    }
}
