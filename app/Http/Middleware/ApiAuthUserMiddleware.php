<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuthUserMiddleware {
	/**
	 * Verifica si existe un token valido de autorizacion 
     * de un usuario en la petición
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {

		$jwtAuth = new \JwtAuth();
        $tokenHeader = $request->header("Authorization");
        
        if (!$tokenHeader) {
			return response()->json([
				"message" => "Necesita una clave autorizada para realizar acciones en la API",
				"errors" => ["Authorization" => "Necesita una clave de autorizacion"],
			], 400);
		}

        $tokenUserValid = $jwtAuth->checkToken($tokenHeader, true);

		if (!$tokenUserValid) {
			return response()->json([
				"message" => "Usuario no Identificado",
				"errors" => ["user" => "Token Inválido"],
			], 401);
		} else {
            $request->request->add(['token' => $tokenUserValid]);
			return $next($request);
		}
	}
}
