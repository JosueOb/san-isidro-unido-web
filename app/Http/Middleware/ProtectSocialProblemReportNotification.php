<?php

namespace App\Http\Middleware;

use Closure;

class ProtectSocialProblemReportNotification
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

        //Se permite que pueda visualizar una notificación de problema social
        //y realizar las acciones de aceptarlo o rechazarlo si el id de la notificación de 
        //pertenece al usuario que está realizando la petición
        $notification = $request->route('notification');
        //Se obtiene al usuario que está realizando la petición
        $user = $request->user();

        if($notification->notifiable_id !== $user->id){
            return abort(404);
        }
        
        return $next($request);
    }
}
