<?php

namespace App\Http\Middleware;

use Closure;

class SocialProblemReport
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
        //Se obtiene el problema social reportado
        $problem = $request->route('problem');
        //se obtiene información adicional del problema social (estado de la solicituda)
        $additional_data = $problem->additional_data;

        //Se verifica si el problema ha sido aceptado o rechazado
        if($additional_data['status_attendance'] === 'aprobado' || $additional_data['status_attendance'] === 'rechazado'){
            return abort(403,'Acción no autorizada');
        }
        return $next($request);
    }
}
