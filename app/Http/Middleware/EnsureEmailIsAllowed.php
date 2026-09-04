<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsAllowed
{
    /**
     * Se a lista de e-mails autorizados (ADMIN_ALLOWED_EMAILS) estiver preenchida,
     * derruba qualquer usuário logado cujo e-mail não esteja nela -- mesmo que a
     * sessão já estivesse ativa antes da lista ser configurada ou alterada.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $permitidos = config('app.admin_allowed_emails');
        $user = $request->user();

        if ($permitidos && $user && ! in_array(strtolower($user->email), $permitidos, true)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('erro', 'Esse e-mail não tem mais acesso a este painel.');
        }

        return $next($request);
    }
}
