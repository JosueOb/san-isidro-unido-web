<?php

namespace App\Http\Middleware;

use Closure;

class EmergencyIsAttendedByPolice
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
        //Se obtiene la emergencia
        $emergency = $request->route('emergency');
        //se obtiene información adicional la emergencia (estado de la solicituda)
        $additional_data = $emergency->additional_data;

        //Se verifica si la emergencia ha sido atendida
        if ($additional_data['status_attendance'] === 'atendido') {
            return $next($request);
        }
        return abort(403, 'Acción no autorizada');
    }
}
