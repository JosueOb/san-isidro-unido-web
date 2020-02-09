<?php

namespace App\Http\Controllers\Api;

use App\Device;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiBaseController;
use Exception;

class ApiDeviceController extends ApiBaseController
{
    
     /**
     * Guarda el dispositivo asociado a un usuario en la API
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function save(Request $request) {
        try {
            $token_decoded = $request->get('token');
            // Verificar si debe guardar un dispositivo asociado al usuario
            $validatorDeviceData = Validator::make($request->all(), [
                "phone_id" => ['required', 'string'],
                'description' => ['string', 'nullable'],
                'phone_model' => ['string', 'nullable'],
            ]);
            //Verificar si el validator falla
            if ($validatorDeviceData->fails()) {
                return $this->sendError(400, "Error en la Petición", $validatorDeviceData->messages());
            }
            //Verificar si el usuario existe
            $user = User::findById($token_decoded->user->id)->first();
            if (is_null($user)) {
                return $this->sendError(500, "Usuario no existe", ["user" => "El usuario no existe"]);
            }
            // Setear los Datos
            $user_id = $token_decoded->user->id;
            $phone_id = $request->get('phone_id');
            $phone_model = $request->get('phone_model');
            $device_description = $request->get('description');
            $deviceVerify = Device::phoneId($phone_id)->first();
            //Si no existe el dispositivo lo agregamos
            if (is_null($deviceVerify)) {
                $device = new Device();
                $device->phone_id = $phone_id;
                $device->phone_model = $phone_model;
                $device->description = $device_description;
                $device->user_id = $user_id;
                $device->save();
                return $this->sendResponse(200, "Dispositivo añadido correctamente", []);
            } else {
                $device_info = [
                   "phone_id" => $phone_id,
                    "phone_model" => $phone_model,
                    "description" => $device_description,
                    "user_id" => $user_id
                ];
                // $deviceVerify->phone_id = $phone_id;
                // $deviceVerify->phone_model = $phone_model;
                // $deviceVerify->description = $device_description;
                // $deviceVerify->user_id = $user_id;
                // $deviceVerify->save();
                $deviceVerify->update($device_info);
                return $this->sendResponse(200, "Dispositivo actualizado correctamente", []);
            }
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el Servidor", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Elimina el dispositivo de un usuario en la API
     * @param \Illuminate\Http\Request $request
     * @param string $device_id
     *
     * @return array
     */
    public function delete(Request $request, $device_id) {
        try {
            //Obtener el usuario decodificado
            $token_decoded = $request->get('token');
            //Verificar si existe el usuario
            $user = User::findById($token_decoded->user->id)->first();
            if (is_null($user)) {
                return $this->sendError(400, "El usuario no existe", ['server_error' => 'Usuario no existe']);
            }
            //Verificar si existe el dispositivo
            $device = Device::findById($device_id)->userId($token_decoded->user->id)->first();
            if (is_null($device)) {
                return $this->sendError(400, "El dispositivo no existe", ['server_error' => 'Dispositivo no existe']);
            }
            //Si existe el dispositivo lo elimino
            $device->delete();
            return $this->sendResponse(200, "Dispositivo eliminado correctamente", []);
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el Servidor", ['server_error' => $e->getMessage()]);
        }
    }
    /**
     * Elimina el dispositivo de un usuario en la API basado en su deviceID
     * @param \Illuminate\Http\Request $request
     * @param string $device_id
     *
     * @return array
     */
    public function deleteByPhoneId(Request $request, $device_phone_id) {
        try {
            //Obtener el usuario decodificado
            $token_decoded = $request->get('token');
            //Verificar si existe el usuario
            $user = User::findById($token_decoded->user->id)->first();
            if (is_null($user)) {
                return $this->sendError(400, "El usuario no existe", ['server_error' => 'Usuario no existe']);
            }
            //Verificar si existe el dispositivo
            $device = Device::findByPhoneId($device_phone_id)->userId($token_decoded->user->id)->first();
            if (is_null($device)) {
                return $this->sendError(400, "El dispositivo no existe", ['server_error' => 'Dispositivo no existe']);
            }
            //Si existe el dispositivo lo elimino
            $device->delete();
            return $this->sendResponse(200, "Dispositivo eliminado correctamente", []);
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el Servidor", ['server_error' => $e->getMessage()]);
        }
    }
}
