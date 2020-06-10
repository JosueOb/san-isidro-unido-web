<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiUserActiveMiddleware
{
    /**
     * Verifica si el usuario esta activo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Se obtiene al miembro de la directiva
        $currentUser =  User::findById($request->token->user->id)->first();
        //Se obtiene el estado de su relación entre roles y usuarios
        $rolMoradorIsActive = $currentUser->getRelationshipStateRolesUsers('morador');
        $rolInvitadoIsActive = $currentUser->getRelationshipStateRolesUsers('invitado');
        $rolPoliciaIsActive = $currentUser->getRelationshipStateRolesUsers('policia');

        if($rolMoradorIsActive || $rolInvitadoIsActive || $rolPoliciaIsActive){
            return $next($request);
        }
        return response()->json([
			"message" => "Usuario Invalido",
			"errors" => ["user" => "usuario no encontrado"],
		], 401);
       
    }
}
