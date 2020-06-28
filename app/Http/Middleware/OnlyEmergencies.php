<?php

namespace App\Http\Middleware;

use App\Category;
use Closure;

class OnlyEmergencies
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
        //Se obtiene al objeto Post de la URL del route
        $emergency = $request->route('post');
        //Se obtiene la categoría de emergencia
        $emergency_category = Category::where('slug', 'emergencia')->first();

        //Se verifica que el id del post obtienio pertenezca a la categoría de emergencia
        if ($emergency->category_id === $emergency_category->id) {
            //se impide visualizar emergencias que no hayan sido abordadas por la policía
            if ($emergency->additional_data['status_attendance'] !== 'pendiente') {
                return $next($request);
            }
        }

        return abort(403, 'Acción no autorizada');
    }
}
