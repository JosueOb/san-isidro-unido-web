<?php

namespace App\Http\Middleware;

use App\Post;
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
        //Se obtiene la notificación del moderador
        $notification = $request->route('notification');

        //Se obtiene información de la emergencia reportada como objeto Post
        $emergency = Post::findOrFail($notification->data['post']['id']);

        //Se verifica el estado de la emergencia, se permite publicar una emergencia si el estado es false
        if ($emergency->state) {
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
