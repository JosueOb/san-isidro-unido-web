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
        //
        //Se obtiene al miembro de la directiva
        $currentUser =  User::findById($request->token->user->id)->first();
        // dd($request->token->user, $currentUser);
        //Se obtiene el estado de su relación entre roles y usuarios
        $rolMoradorIsActive = $currentUser->getRelationshipStateRolesUsers('morador');
        $rolInvitadoIsActive = $currentUser->getRelationshipStateRolesUsers('invitado');
        // dd($rolMoradorIsActive, $rolInvitadoIsActive);

        if($rolMoradorIsActive|| $rolInvitadoIsActive){
            return $next($request);
        }
        return response()->json([
			"message" => "Usuario Invalido",
			"errors" => ["user" => "usuario no encontrado"],
		], 401);
       
    }
}
