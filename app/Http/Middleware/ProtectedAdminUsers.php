<?php

namespace App\Http\Middleware;

use Closure;

class ProtectedAdminUsers
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
        $getUserRole = $request->route('member')->getRol()->name;

        if($getUserRole == 'Administrador'){
            return abort(404);
        }
        return $next($request);
    }
}
