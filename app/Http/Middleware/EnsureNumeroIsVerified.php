<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verificação por segundo canal (WhatsApp). Desde 31/08/2026, essa é a
 * única verificação obrigatória -- a de e-mail foi retirada do fluxo
 * (ver routes/web.php). Só deixa passar se numero_verified_at estiver
 * preenchido.
 */
class EnsureNumeroIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->numero_verified_at) {
            return redirect()->route('numero.verification.notice');
        }

        return $next($request);
    }
}
