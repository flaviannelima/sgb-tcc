<?php

namespace App\Http\Middleware;

use App\Leitor;
use Closure;

class Historico
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
       
        return ($request->user()->coordenador()->first() && $request->user()->coordenador()->first()->ativo) || 
        ($request->user()->atendente()->first() && $request->user()->atendente()->first()->ativo) || 
        ($request->user()->id == $request->route('leitor')->user) ? $next($request)
        :   response('Você não possui permissão para acessar essa funcionalidade', 403);
     
    }
}
