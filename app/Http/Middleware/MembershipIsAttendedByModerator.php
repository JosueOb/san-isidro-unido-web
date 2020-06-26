<?php

namespace App\Http\Middleware;

use App\Membership;
use Closure;

class MembershipIsAttendedByModerator
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
        //Se obtiene la notificación del moderador
        $notification = $request->route('notification');

        //Se obtiene información de la afiliación como objeto Membership// registro de la BDD
        $membership = Membership::findOrFail($notification->data['membership']['id']);

        //Se verifica si la solicitud está con estado pendiente para permitir su aprobación o rechazo
        if ($membership->status_attendance === 'pendiente') {
            return $next($request);
        }
        //caso contrario se retorna un error 403
        return abort(403, 'Acción no autorizada');
    }
}
