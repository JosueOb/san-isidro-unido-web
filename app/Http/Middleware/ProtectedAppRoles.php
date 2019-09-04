<?php

namespace App\Http\Middleware;

use Closure;

class ProtectedAppRoles
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
        if($request->route('role')->mobile_app){
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
