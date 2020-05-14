<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class Logout
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
        $logout = false;
        $getUser = $request->user();

        if(!$getUser->hasSomeActiveWebSystemRole()){
            Auth::logout();
            return redirect('login');
        }

        return $next($request);
    }
}
