<?php

namespace App\Support;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailVerificationCode
{
    private const MINUTOS_VALIDADE = 15;

    public static function gerarEEnviar(User $user): void
    {
        $codigo = (string) random_int(100000, 999999);

        $user->forceFill([
            'verification_code' => $codigo,
            'verification_code_expires_at' => now()->addMinutes(self::MINUTOS_VALIDADE),
        ])->save();

        Mail::to($user->email)->send(new VerificationCodeMail($codigo));
    }

    public static function confirmar(User $user, ?string $codigoInformado): bool
    {
        if (! $user->verification_code || ! $codigoInformado) {
            return false;
        }

        if ($user->verification_code_expires_at && $user->verification_code_expires_at->isPast()) {
            return false;
        }

        if (! hash_equals($user->verification_code, trim($codigoInformado))) {
            return false;
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ])->save();

        return true;
    }
}
