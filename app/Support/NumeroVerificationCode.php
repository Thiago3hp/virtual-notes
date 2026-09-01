<?php

namespace App\Support;

use App\Models\User;
use App\Models\WhatsappOutboxMessage;

class NumeroVerificationCode
{
    private const MINUTOS_VALIDADE = 15;

    /**
     * Retorna false (sem gerar nada) se o usuário não tem um numero_tecnico
     * válido salvo -- evita colocar uma mensagem com número vazio na fila
     * whatsapp_outbox (o bot rejeita e marca como falhou de qualquer
     * forma, mas é melhor nem chegar a isso).
     */
    public static function gerarEEnfileirar(User $user): bool
    {
        $numero = TecnicoNumeroValidator::normalizar($user->numero_tecnico);

        if ($numero === '') {
            return false;
        }

        $codigo = (string) random_int(100000, 999999);

        $user->forceFill([
            'numero_verification_code' => $codigo,
            'numero_verification_expires_at' => now()->addMinutes(self::MINUTOS_VALIDADE),
        ])->save();

        WhatsappOutboxMessage::create([
            'numero' => $numero,
            'mensagem' => "Seu código de verificação do Virtual Notes é: {$codigo}\n\nEle expira em 15 minutos. Se você não solicitou isso, ignore essa mensagem.",
            'status' => 'pendente',
        ]);

        return true;
    }

    public static function confirmar(User $user, ?string $codigoInformado): bool
    {
        if (! $user->numero_verification_code || ! $codigoInformado) {
            return false;
        }

        if ($user->numero_verification_expires_at && $user->numero_verification_expires_at->isPast()) {
            return false;
        }

        if (! hash_equals($user->numero_verification_code, trim($codigoInformado))) {
            return false;
        }

        $user->forceFill([
            'numero_verified_at' => now(),
            'numero_verification_code' => null,
            'numero_verification_expires_at' => null,
        ])->save();

        return true;
    }
}
