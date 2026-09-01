<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Support\TecnicoNumeroValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Segundo fator de acesso além de email/senha: o login exige um número
 * de técnico.
 *
 * - Enquanto a conta ainda não passou pela verificação completa (e-mail
 *   + WhatsApp), aceita qualquer número da lista geral autorizada --
 *   nesse ponto o número ainda não está "vinculado" a ninguém.
 * - Depois que numero_verified_at é preenchido, a conta só aceita O
 *   PRÓPRIO número dela. Isso é o que impede duas contas diferentes de
 *   usarem o mesmo número autorizado depois de um deles já ter
 *   reivindicado ele.
 *
 * Deliberadamente retorna null (falha genérica, mesma mensagem do
 * Fortify pra credenciais erradas) em qualquer caso de falha -- não dá
 * pra um atacante saber qual das checagens não passou.
 */
class AuthenticateWithTecnicoNumero
{
    public function __invoke(Request $request): ?User
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check((string) $request->password, $user->password)) {
            return null;
        }

        $numeroInformado = TecnicoNumeroValidator::normalizar($request->input('numero_tecnico'));

        if ($numeroInformado === '') {
            return null;
        }

        if ($user->numero_verified_at) {
            if ($numeroInformado !== TecnicoNumeroValidator::normalizar($user->numero_tecnico)) {
                return null;
            }
        } else {
            if (! TecnicoNumeroValidator::autorizado($numeroInformado)) {
                return null;
            }

            // Enquanto não está verificado, o número ainda não está
            // "reivindicado" -- mantém numero_tecnico sempre igual ao que
            // a pessoa digitou agora no login. Sem isso, se o valor salvo
            // no cadastro estiver vazio/desatualizado, a verificação por
            // WhatsApp tenta enviar pra um número vazio (ver
            // NumeroVerificationCode::gerarEEnfileirar).
            if (TecnicoNumeroValidator::normalizar($user->numero_tecnico) !== $numeroInformado) {
                $user->forceFill(['numero_tecnico' => $numeroInformado])->save();
            }
        }

        return $user;
    }
}
