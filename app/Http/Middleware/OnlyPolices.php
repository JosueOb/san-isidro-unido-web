<?php

namespace App\Http\Middleware;

use Closure;

class OnlyPolices
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
        $isPolice = $request->route('user')->getASpecificRole('policia') ? true : false;
        
        if ($isPolice) {
            return $next($request);
        }
        return abort(403, 'Acción no autorizada');
    }
}
