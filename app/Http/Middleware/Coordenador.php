<?php

namespace App\Http\Middleware;

use Closure;

class Coordenador
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $request->user()->coordenador()->first() && 
        $request->user()->coordenador()->first()->ativo ? $next($request)
        :   response('Você não possui permissão para acessar essa funcionalidade', 403);
    }
}
