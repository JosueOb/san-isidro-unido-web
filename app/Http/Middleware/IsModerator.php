<?php

namespace App\Http\Middleware;

use Closure;

class IsModerator
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
        $getUser = $getUser = $request->route('user');

        if(!$getUser->getASpecificRole('moderador')){
            return abort(403, 'Acción no autorizada');
        }
        return $next($request);
    }
}
