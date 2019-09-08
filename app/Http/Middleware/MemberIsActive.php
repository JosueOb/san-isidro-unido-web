<?php

namespace App\Http\Middleware;

use Closure;

class MemberIsActive
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
        ////Se retorna una excepción HTTP 403, en caso de que el usuario esté
        //inactivo con respecto al rol que cumple, evitando enviar el formulario de actualización
        //Se obtiene al miembro de la directiva
        $getUser = $request->route('member');
        //Se obtiene el estado de su relación entre roles y usuarios
        $getStatusUserRole = $getUser->getASpecificRole('directivo')->pivot->state;

        if(!$getStatusUserRole){
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
