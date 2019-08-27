<?php

namespace App\Http\Middleware;

use Closure;

class UserIsActive
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
        //inactivo, evitando enviar el formulario de actualización
        //Se obtiene el id del miembro de la directiva
        $getUserState = $request->route('member')->state;

        if(!$getUserState){
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
