<?php

namespace App\Http\Middleware;

use App\Category;
use Closure;

class OnlySocialProblems
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
        $social_problem = $request->route('post');
        //Se obtiene la categoría de problema social
        $category_social_problem = Category::where('slug', 'problema')->first();

        //Se verifica que el id del post obtienio pertenezca a la categoría de problema social
        if ($social_problem->category_id === $category_social_problem->id) {
            //se impide visualizar problemas sociales con estado pendiente
            if($social_problem->additional_data['status_attendance'] !== 'pendiente'){
                return $next($request);
            }
        }

        return abort(403, 'Acción no autorizada');
    }
}
