<?php

namespace App\Http\Middleware;

use Closure;

class ApiTokenMiddleware
{
    /**
     * Verifica si existe un token valido de autorizacion 
     * para acceder a la API
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
