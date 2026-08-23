<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Números de técnico autorizados
    |--------------------------------------------------------------------------
    |
    | Segundo fator de acesso: além de email/senha, o login exige um número
    | de técnico que bata com um dos autorizados aqui. A lista fica só nas
    | variáveis de ambiente (TECNICO_NUMBERS, separado por vírgula) --
    | nunca comitada em nenhum arquivo, definida direto no serviço do
    | Railway (ou no .env local, fora do controle de versão).
    |
    */

    'numeros_autorizados' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('TECNICO_NUMBERS', ''))
    ))),

];
