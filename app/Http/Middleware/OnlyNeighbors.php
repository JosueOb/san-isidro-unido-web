<?php

namespace App\Http\Middleware;

use Closure;

class OnlyNeighbors
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
        $isNeighbor = $request->route('user')->getASpecificRole('morador') ? true : false;
        
        if ($isNeighbor) {
            return $next($request);
        }
        return abort(403, 'Acción no autorizada');
    }
}
