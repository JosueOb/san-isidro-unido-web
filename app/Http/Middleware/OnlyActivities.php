<?php

namespace App\Http\Middleware;

use App\Category;
use Closure;

class OnlyActivities
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
        $report_activity = $request->route('post');
        //Se obtiene la categoría de emergencia
        $report_activity_category = Category::where('slug', 'informe')->first();

        //Se verifica que el id del post obtienio pertenezca a la categoría de informe
        if ($report_activity->category_id === $report_activity_category->id) {
            return $next($request);
        }

        return abort(403, 'Acción no autorizada');
    }
}
