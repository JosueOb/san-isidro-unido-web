<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\SocialProfile;
use App\User;
use Exception;

class ApiSocialProfileController extends ApiBaseController
{
    
    /**
     * Elimina el perfil social asociado a un usuario
     * @param \Illuminate\Http\Request $request
     * @param string $social_profile_id
     *
     * @return array
     */
    public function delete(Request $request, $social_profile_id) {
        try {
            $token_decoded = $request->get('token');
            $user = User::findById($token_decoded->user->id)->first();
            //Verificar si el usuario existe
            if(!is_null($user)){
                $social_profile = SocialProfile::findById($social_profile_id)->userId($token_decoded->user->id)->first();
                //Verificar si existe el problema social
                if (!is_null($social_profile)) {
                    $social_profile->delete();
                    return $this->sendResponse(200, "Perfil Social eliminado correctamente", []);
                }
                //Si no existe perfil social retorno error
                return $this->sendError(400, "El usuario no existe", ['server_error' => 'Perfil Social no existe']);
            }       
            // Si no existe usuario retorno error   
            return $this->sendError(400, "El perfil social no existe", ['server_error' => 'Perfil Social no existe']);
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el Servidor", ['server_error' => $e->getMessage()]);
        }
    }
}
