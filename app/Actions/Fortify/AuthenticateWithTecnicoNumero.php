<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Segundo fator de acesso além de email/senha: o login só passa se o
 * número de técnico informado bater com um dos autorizados em
 * config('tecnicos.numeros_autorizados') (vem só de env, nunca comitado).
 *
 * Deliberadamente retorna null (falha genérica, mesma mensagem do
 * Fortify pra credenciais erradas) tanto se o email/senha estiverem
 * errados quanto se o número de técnico não bater -- não dá pra um
 * atacante saber qual das duas coisas falhou.
 */
class AuthenticateWithTecnicoNumero
{
    public function __invoke(Request $request): ?User
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check((string) $request->password, $user->password)) {
            return null;
        }

        if (! $this->numeroAutorizado($request->input('numero_tecnico'))) {
            return null;
        }

        return $user;
    }

    private function numeroAutorizado(?string $numeroInformado): bool
    {
        $numeroInformado = $this->normalizar($numeroInformado);

        if ($numeroInformado === '') {
            return false;
        }

        $autorizados = array_map($this->normalizar(...), config('tecnicos.numeros_autorizados', []));

        return in_array($numeroInformado, $autorizados, true);
    }

    /**
     * Compara só os dígitos, pra "(86) 99999-9999" e "5586999999999"
     * baterem mesmo formatados de jeitos diferentes.
     */
    private function normalizar(?string $numero): string
    {
        return preg_replace('/\D+/', '', (string) $numero) ?? '';
    }
}
