<?php

namespace App\Http\Middleware;

use Closure;

class PreventMakingChangesToYourself
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
        // Se impide que el usuario directivo autenticado se modifique o elimine 
        //a si mismo en el listado de los directivos
        if($request->user()->id === $request->route('user')->id){
            return abort(403,'Acción no autorizada');
        }
        return $next($request);
    }
}
