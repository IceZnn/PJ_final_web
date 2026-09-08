<?php

namespace App\Http\Middleware;

use App\Models\TokenUsuario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenUsuarioMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $tokenUsuario = TokenUsuario::where('token', $token)
            ->where('valido_ate', '>', now())
            ->first();

        if (!$tokenUsuario) {
            return response()->json([
                'mensagem' => 'Token inválido ou expirado.',
            ], 401);
        }

        $request->attributes->set('usuario', $tokenUsuario->usuario_id);

        return $next($request);
    }
}
