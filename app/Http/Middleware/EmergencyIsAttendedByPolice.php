<?php

namespace App\Http\Middleware;

use App\Post;
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
        //Se obtiene la notificación del moderador
        $notification = $request->route('notification');

        //Se obtiene información de la emergencia reportada como objeto Post
        $emergency = Post::findOrFail($notification->data['post']['id']);

        //Se obtiene el estado de la emergencia
        $emergency_status_attendance = $emergency->additional_data['status_attendance'];

        //Se verifica si el reporte de emergencia está con estado aprobado para permitir su publicación por parte del moderador
        if ($emergency_status_attendance === 'atendido') {
            return $next($request);
        }
        //caso contrario se retorna un error 403
        return abort(403, 'Acción no autorizada');
    }
}
