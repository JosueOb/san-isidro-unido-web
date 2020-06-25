<?php

namespace App\Http\Middleware;

use Closure;

class EmergencyIsPublishedByModerator
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
        //Se obtiene a ala emergencia
        $emergency = $request->route('emergency');

        //Se verifica el estado de la emergencia
        if ($emergency->state) {
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
