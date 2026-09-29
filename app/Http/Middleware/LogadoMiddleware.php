<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use App\Models\TokenUsuario;


class LogadoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Cookie::has('token')) {
            $token = Cookie::get('token');
            $tokenUsuario = TokenUsuario::where('token','=', $token)
                ->where('valido_ate', '>', now())
                ->first();

            if ($tokenUsuario ){
                $request->attributes->set('usuario', $tokenUsuario->usuario_id);
                return $next($request);
            }else{
                Cookie::forget('token');
                return redirect()->route('login');
            }

        }

        return redirect()->route('login');

    }
}
