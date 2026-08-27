<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mesmo espírito do middleware 'verified' nativo do Laravel, só que pro
 * segundo canal (WhatsApp), não o e-mail. Só deixa passar se
 * numero_verified_at estiver preenchido.
 */
class EnsureNumeroIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->email_verified_at) {
            // Se o e-mail nem foi confirmado ainda, deixa o middleware
            // 'verified' padrão cuidar disso primeiro.
            return $next($request);
        }

        if (! $user->numero_verified_at) {
            return redirect()->route('numero.verification.notice');
        }

        return $next($request);
    }
}
