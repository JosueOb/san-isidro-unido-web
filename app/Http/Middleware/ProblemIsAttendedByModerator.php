<?php

namespace App\Http\Middleware;

use App\Post;
use Closure;
use Illuminate\Support\Facades\Auth;

class ProblemIsAttendedByModerator
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
        //Se obtiene al moderador
        $moderator =  Auth::user();

        //Se obtiene información del problema social reportado como objeto Post
        $social_problem = Post::findOrFail($notification->data['post']['id']);

         //Se obtiene el estado del problema social
         $social_problem_status_attendance = $social_problem->additional_data['status_attendance'];

        //Se verifica si el reporte de problema social está con estado pendiente para permitir su aprobación o rechazo por parte del moderador
        if($social_problem_status_attendance === 'pendiente'){
            //Se verifica que el usuario (morador) que reportó el problema social sea diferente al moderador
            if($social_problem->user_id !== $moderator->id){
                return $next($request);
            }
        }
        //caso contrario se retorna un error 403
        return abort(403,'Acción no autorizada');
    }
}
