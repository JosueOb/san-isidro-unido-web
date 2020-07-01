<?php

namespace App\Http\Middleware;

use App\Category;
use Closure;

class OnlyEvents
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
        $event = $request->route('post');
        //Se obtiene la categoría de evento
        $event_category = Category::where('slug', 'evento')->first();

        //Se verifica que el id del post obtienio pertenezca a la categoría de evento
        if ($event->category_id === $event_category->id) {
            return $next($request);
        }

        return abort(403, 'Acción no autorizada');
    }
}
