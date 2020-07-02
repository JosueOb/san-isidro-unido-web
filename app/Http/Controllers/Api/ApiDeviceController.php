<?php

namespace App\Http\Controllers\Api;

use App\Device;
use App\Http\Controllers\Api\ApiBaseController;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiDeviceController extends ApiBaseController
{

    /**
     * Guarda el dispositivo asociado a un usuario en la API
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function save(Request $request)
    {
        try {
            $token_decoded = $request->get('token');
            // Verificar si debe guardar un dispositivo asociado al usuario
            $validatorDeviceData = Validator::make($request->all(), [
                "phone_id" => ['required', 'string','max:100'],
                'description' => ['string', 'nullable'],
                'phone_model' => ['string', 'nullable','max:100'],
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
            $phone_platform = $request->get('phone_platform', '');
            $device_description = $request->get('description', '');
            //Guardar Dispositivo
            $this->saveDevice($phone_id, $phone_model, $phone_platform, $description, $user_id);
            $deviceVerify = Device::phoneId($phone_id)->first();
            //Si no existe el dispositivo lo agregamos
            if (is_null($deviceVerify)) {
                return $this->sendResponse(200, "Dispositivo añadido correctamente", []);
            } else {
                return $this->sendResponse(200, "Dispositivo actualizado correctamente", []);
            }
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el Servidor", ['server_error' => $e->getMessage()]);
        }
    }

    public function saveDevice($phone_id, $phone_model, $phone_platform, $description, $user_id)
    {
        $deviceVerify = Device::phoneId($phone_id)->userId($user_id)->first();
        //Si no existe el dispositivo lo agregamos
        if (is_null($deviceVerify)) {
            $device = new Device();
            $device->phone_id = $phone_id;
            $device->phone_model = $phone_model;
            $device->phone_platform = $phone_platform;
            $device->description = $description;
            $device->user_id = $user_id;
            return $device->save();
        } else {
            $device_info = [
                "phone_id" => $phone_id,
                "phone_model" => $phone_model,
                "description" => $description,
                "user_id" => $user_id,
            ];
            return $deviceVerify->update($device_info);
        }
    }

    /**
     * Elimina el dispositivo de un usuario en la API
     * @param \Illuminate\Http\Request $request
     * @param string $device_id
     *
     * @return array
     */
    public function delete(Request $request, $device_id)
    {
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
    public function deleteByPhoneId(Request $request, $device_phone_id)
    {
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
