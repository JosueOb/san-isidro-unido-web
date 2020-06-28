<?php

namespace App\Http\Middleware;

use Closure;

class AllowToAttendOrRejectProblemsAddressedByModerator
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
        //Se obtiene al problema social de la URL del route, como objeto Post
        $social_problem = $request->route('post');
        //Se permite atender o rechazar problemas sociales, cuando el problema haya sido aprobado por el moderador (state = true)
        // y el estado de abordado sea aprobado
        if($social_problem->state && $social_problem->additional_data['status_attendance'] === 'aprobado'){
            return $next($request);
        }

        return abort(403, 'Acción no autorizada');
    }
}
