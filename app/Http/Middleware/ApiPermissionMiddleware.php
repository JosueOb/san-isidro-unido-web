<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class ApiPermissionMiddleware {
	/**
	 * Verifica si el usuario puede crear un reporte
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string  $role
	 * @return mixed
	 */
	// public function handle($request, Closure $next, $role) {
	public function handle($request, Closure $next, ...$roles) {

		$jwtAuth = new \JwtAuth();
        $tokenHeader = $request->header("Authorization");
        if (!$tokenHeader) {
			return $this->sendTokenRequiredResponse();
        }        
        
        $token_decoded = $jwtAuth->checkToken($tokenHeader, true);
    
        if (!$token_decoded) {
			return $this->sendTokenInvalidResponse();
		}
		
		foreach ($roles as $rol) {
			// if ($request->user()->getTipoUsuario($request->user()->tipo_usuario_id)->getNombreTipoUsuario() == $rol) {
			// 	return $next($request);
			// }
			$userRol = User::findById($token_decoded->user->id)->first();
	
			if(!$userRol){			
				return $this->sendForbiddenResponse();
			}
			//Verificar Rol Morador
			$hasRol = $userRol->hasRole($rol);
			if($hasRol){			
				return $next($request);
			}
		}
		return $this->sendForbiddenResponse();
        
    }
    
    public function sendForbiddenResponse(){
		return response()->json([
			"message" => "No tienes permiso para realizar esta acción"
		], 403);
    }

    public function sendTokenInvalidResponse(){
		return response()->json([
			"message" => "Usuario no Identificado",
			"errors" => ["user" => "Token Inválido"],
		], 401);
	}
    
    public function sendTokenRequiredResponse(){
		return response()->json([
			"message" => "Necesita una clave autorizada para realizar acciones en la API",
			"errors" => ["Authorization" => "Necesita una clave de autorizacion"],
		], 400);
	}

}
