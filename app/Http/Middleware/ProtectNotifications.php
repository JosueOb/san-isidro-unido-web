<?php

namespace App\Http\Middleware;

use Closure;

class ProtectNotifications
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
        //Se permite que pueda acceder a las notificación pertenecientes al moderador que inicio sessión
        $notification = $request->route('notification');
        //Se obtiene al moderador que está realizando la petición
        $user = $request->user();

        if ($notification->notifiable_id !== $user->id) {
            return abort(404);
        }

        return $next($request);
    }
}
