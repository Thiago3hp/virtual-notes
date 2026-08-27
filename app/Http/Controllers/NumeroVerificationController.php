<?php

namespace App\Http\Controllers;

use App\Support\NumeroVerificationCode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NumeroVerificationController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->numero_verified_at) {
            return redirect()->intended(route('dashboard'));
        }

        // Se ainda não tem nenhum código pendente (ex: acabou de sair do
        // passo do e-mail), gera e enfileira agora.
        if (! $user->numero_verification_code) {
            NumeroVerificationCode::gerarEEnfileirar($user);
        }

        return Inertia::render('auth/VerifyNumeroCode', [
            'numero' => $user->numero_tecnico,
        ]);
    }

    public function confirmar(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (! NumeroVerificationCode::confirmar($user, $request->input('code'))) {
            return back()->withErrors(['code' => 'Código inválido ou expirado.']);
        }

        return redirect()->route('dashboard')->with('success', 'Número de técnico verificado com sucesso!');
    }

    public function reenviar(Request $request)
    {
        $user = $request->user();

        if ($user->numero_verified_at) {
            return redirect()->route('dashboard');
        }

        NumeroVerificationCode::gerarEEnfileirar($user);

        return back()->with('success', 'Um novo código foi enviado pro seu WhatsApp.');
    }
}
