<?php

namespace App\Support;

/**
 * Checa um número de técnico contra a lista autorizada em
 * config('tecnicos.numeros_autorizados') (vem só de env, nunca comitada).
 *
 * Usado tanto no login (AuthenticateWithTecnicoNumero) quanto no cadastro
 * (CreateNewUser) -- centralizado aqui pra não duplicar a lógica de
 * normalização em dois lugares e correr risco dos dois desalinharem.
 */
class TecnicoNumeroValidator
{
    public static function autorizado(?string $numeroInformado): bool
    {
        $numeroInformado = self::normalizar($numeroInformado);

        if ($numeroInformado === '') {
            return false;
        }

        $autorizados = array_map(self::normalizar(...), config('tecnicos.numeros_autorizados', []));

        return in_array($numeroInformado, $autorizados, true);
    }

    /**
     * Compara só os dígitos, pra "(86) 99999-9999" e "5586999999999"
     * baterem mesmo formatados de jeitos diferentes.
     */
    public static function normalizar(?string $numero): string
    {
        return preg_replace('/\D+/', '', (string) $numero) ?? '';
    }
}
