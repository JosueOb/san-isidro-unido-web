<?php

namespace App\Http\Middleware;

use Caffeinated\Shinobi\Models\Permission as ModelsPermission;
use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $slug)
    {
        //PERMISO
        //Se obtiene al permiso como objeto
        $permission = ModelsPermission::where('slug', $slug)->first();
        //Se obtiene los roles que tiene el permiso
        $permission_roles = $permission->roles;

        //USUARIO
        //Se obtiene al usuario que está realizando la petición
        $user = $request->user();
        //Se obtiene los roles que tiene el usuario
        $user_roles = $user->roles;

        //Se obtienen los roles tanto del usuario y permiso que tienen en común
        $common_roles = $permission_roles->intersect($user_roles);

        //Se verifica si el usuario tiene permiso y su el estado de la relación usario y rol, permitiendo el realizar el request
        // cuendo tenga permiso y si el estado de su relacion rol usuario esté activa
        if($user->can($permission->slug) && $this->checkRoleState($common_roles, $user)){
            return $next($request);
        }else{
            // dd('el usuario no tiene el permiso de ingresar a esta ruta');
            return abort(403, 'This action is unauthorized.');
        }
    }

    //Se verifica que de los roles obtenidos, uno de ellos tenga el usuario activado en su relación de rol y usuario
    private function checkRoleState($roles, $user){
        $state = false;
        foreach($roles as $role){
            if($user->getRelationshipStateRolesUsers($role->slug)){
                $state = true;
            }
        }
        return $state;
    }
}
