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
        $getUserRole = $request->route('user')->getASpecificRole('admin');
        // dd($getUserRole);

        if($getUserRole){
            return abort(403,'Acción no autorizada');
        }
        return $next($request);
    }
}
