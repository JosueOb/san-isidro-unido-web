<?php

namespace App\Http\Middleware;

use Closure;

class ProtectPrivateRoles
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
        //Se verifica si el rol es privado para evitar editarlo o eliminarlo
        if($request->route('role')->private){
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
