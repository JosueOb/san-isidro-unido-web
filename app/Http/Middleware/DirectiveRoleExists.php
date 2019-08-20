<?php

namespace App\Http\Middleware;

use Caffeinated\Shinobi\Models\Role;
use Closure;

class DirectiveRoleExists
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
        //Se determina si el rol Directivo/a se encuentra registrado
        $directiveRoleExists= Role::whereIn('name', ['Directivo', 'Directiva'])->exists();
        //En caso de que no se encuentre registrado se redirecciona al usuario para que registre el respectivo rol
        if(!$directiveRoleExists){
            return redirect()->route('roles.index')
            ->with('info','Debe registrar el rol directivo/a para acceder a su respetivo módulo.');
        }
        
        return $next($request);

    }
}
