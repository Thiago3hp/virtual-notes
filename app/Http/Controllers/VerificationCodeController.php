<?php

namespace App\Http\Controllers;

use App\Support\EmailVerificationCode;
use App\Support\NumeroVerificationCode;
use App\Support\TecnicoNumeroValidator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VerificationCodeController extends Controller
{
    /**
     * Tela de "digite o código". Registrada como a rota 'verification.notice',
     * pra onde o middleware 'verified' do Laravel manda quem ainda não
     * confirmou o e-mail.
     */
    public function show(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        return Inertia::render('auth/VerifyEmailCode', [
            'email' => $request->user()->email,
        ]);
    }

    public function confirmar(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (! EmailVerificationCode::confirmar($user, $request->input('code'))) {
            return back()->withErrors(['code' => 'Código inválido ou expirado.']);
        }

        if (! TecnicoNumeroValidator::autorizado($user->numero_tecnico)) {
            return back()->withErrors(['code' => 'O número de técnico associado a essa conta não está mais autorizado. Fale com um administrador.']);
        }

        // E-mail confirmado -- falta o segundo canal (WhatsApp) antes de
        // liberar o painel de verdade.
        NumeroVerificationCode::gerarEEnfileirar($user);

        return redirect()->route('numero.verification.notice');
    }

    public function reenviar(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        EmailVerificationCode::gerarEEnviar($user);

        return back()->with('success', 'Um novo código foi enviado pro seu e-mail.');
    }
}
