<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use App\User;


/**
 * @OA\Info(title="San Isidro Unido API", description="Documentación para la API del proyecto de San Isidro Unido",version="1.0",
 *   @OA\Contact(
 *     email="stalinct97@gmail.com"
 *   )
 * )
 *
 * @OA\Server(url="http://localhost:8000/")
 */
class ApiBaseController extends Controller {
	/**
	 * Envia una respuesta correcta en Formato JSON
	 * @param integer $code
	 * @param string $message
	 * @param array $data
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sendResponse($code = 200, $message = '', $data = []) {
		$responseApi = [
			'message' => $message,
            'code' => $code,
            "status" => "success"
		];
		$responseApi["data"] = $data;
		return response()->json($responseApi, $code, [] ,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * Envia una respuesta correcta de una paginación en Formato JSON
	 * @param integer $code
	 * @param string $message
	 * @param array $data
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sendPaginateResponse($code = 200, $message = '', $data = []) {
		$responseApi = [
			'message' => $message,
            'code' => $code,
            "status" => "success"
		];
		$new_response_api = array_merge($responseApi, $data);
		return response()->json($new_response_api, $code, [] ,JSON_UNESCAPED_UNICODE);
	}

	/**
	 *Envia una respuesta de error en Formato JSON
	 * @param integer $code
	 * @param string $message
	 * @param array $errors
	 * @return \Illuminate\Http\Response
	 */
	public function sendError($code = 500, $message = '', $errors = []) {
		$responseApi = [
			'message' => $message,
            'code' => $code,
            "status" => "error"
		];
        $responseApi["errors"] = $errors;
		return response()->json($responseApi, $code);
    }
    
    public function sendDebugResponse($data = [], $code = 500){
        return response()->json($data, $code, [] ,JSON_UNESCAPED_UNICODE);
	}
	
	public function checkReportPermisssion($token = null){		
		$userRol = User::findById($token->user->id)->first();
		//Verificar Usuario Rol
		if(!$userRol){			
			return $this->sendForbiddenResponse();
		}
		//Verificar Rol Morador
		$hasRol = $userRol->hasRole('morador');
		if(!$hasRol){			
			return $this->sendForbiddenResponse();
		}
		return true;
	}

	public function sendForbiddenResponse(){
		return response()->json([
			"message" => "No tienes permiso para realizar esta acción"
		], 403, [] ,JSON_UNESCAPED_UNICODE);
	}
}