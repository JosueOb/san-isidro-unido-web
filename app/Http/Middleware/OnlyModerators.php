<?php

namespace App\Http\Middleware;

use Closure;

class OnlyModerators
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
        $isModerator = $request->route('user')->getASpecificRole('moderador') ? true : false;
        
        if ($isModerator) {
            return $next($request);
        }
        return abort(403, 'Acción no autorizada');
    }
}
