<?php

namespace App\Http\Middleware;

use Closure;

class ProtectedNeighborUsers
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
        $getUser = $request->route('user');
        
        //Se verifica que el usuario no tenga asignado ningún rol del sistema web y tenga el rol 
        //de morador

        if(!count($getUser->getWebSystemRoles()) && $getUser->getASpecificRole('morador')){
            return abort(403,'Acción no autorizada');
        }
        return $next($request);
    }
}
