<?php

namespace App\Http\Middleware;

use Closure;

class CheckRoleState
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        //Se obtiene el usuario autenticado y realizando la petición HTTP
        $getUser = $request->user();
        // dd($role);

        //Se verifica el estado de su relacíión con el rol de moderador
        if(!$getUser->getRelationshipStateRolesUsers($role)){
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
