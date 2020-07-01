<?php

namespace App\Http\Middleware;

use Closure;

class OnlyMembers
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
        $isDirective = $request->route('user')->getASpecificRole('directivo') ? true : false;
        
        if ($isDirective) {
            return $next($request);
        }
        return abort(403, 'Acción no autorizada');
    }
}
