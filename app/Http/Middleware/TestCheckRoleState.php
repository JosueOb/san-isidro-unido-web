<?php

namespace App\Http\Middleware;

use Closure;

class TestCheckRoleState
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
        // dd($request->user()->getFullName());
        // $getUserAuth = $request->user();
        // $getUserRoles = $getUserAuth->getWebSystemRoles();
        
        // dd($getUserRoles);

        // dd( $request->route()->action['as']);
        // dd($request->user()->getFullName());
        // dd($request->user()->roles);
        // dd($request->user()->can('events.index'));
        // dd( $request->route() );

        return $next($request);
    }
}
