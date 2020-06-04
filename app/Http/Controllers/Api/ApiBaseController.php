<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use App\User;

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
	
	/**
	 *Envia una respuesta de error de Debug en Formato JSON
	 * @param array $data
	 * @param integer $code
	 * @return \Illuminate\Http\Response
	 */
    public function sendDebugResponse($data = [], $code = 500){
        return response()->json($data, $code, [] ,JSON_UNESCAPED_UNICODE);
	}	
}