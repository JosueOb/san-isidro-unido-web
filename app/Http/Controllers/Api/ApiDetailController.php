<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Rules\Api\DetailType;
use App\Detail;
use App\User;
use App\Post;
use App\Http\Controllers\Api\ApiBaseController;
use Exception;

class ApiDetailController extends ApiBaseController
{

    /**
     * Crea el detalle de una Publicacion en la API
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function create(Request $request) {
        $token_decoded = $request->get('token');
        try {
            $validate = Validator::make($request->all(), [
                "type" => ['required', 'string', new DetailType()],
                "post_id" => ['required', 'numeric']
            ]);
            $type = $request->get('type');
            $post_id = $request->get('post_id');
            //Validar Request
            if ($validate->fails()) {
                return $this->sendError(400, "Datos no Válidos", ['server_error' => $validate->messages()]);
            }
            //Comprobar Usuario Existe
            $count_user = User::findById($token_decoded->user->id)->count();
            // if (User::where('email', '=', Input::get('email'))->count() > 0) { // user found } 
            if ($count_user == 0) {
                return $this->sendError(400, "El Usuario no existe", ['user' => 'usuario no existe']);
            }
            //Comprobar si el post existe
            $count_post = Post::findById($post_id)->count();
            if ($count_post == 0) {
                return $this->sendError(400, "El Post no existe", ['posts' => 'Post no existe']);
            }
            //Verificar si Existe el Detalle
            $count_detail = Detail::userId($token_decoded->user->id)->type($type)->postId($post_id)->count();
            if ($count_detail > 0) {
                return $this->sendError(400, "El $type ya existe", ['detail' => "El Detalle de tipo $type no existe"]);
            }
            //Crear un nuevo Detalle
            $detail = new Detail();
            $detail->post_id = $post_id;
            $detail->user_id = $token_decoded->user->id;
            $detail->type = $type;
            //Guardar el detalle en la BDD
            $detail->save();
            return $this->sendResponse(200, "Detalle Creado Correctamente", []);
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el Servidor", ['server_error' => $e->getMessage()]);
        }
    }

    // Eliminar el detalle de una publicación
     /**
     * Elimina el detalle de una Publicacion en la API
     * @param integer $id
     *
     * @return array
     */
    public function delete($id) {
        try {
            $detail = Detail::postId($id)->first();
            if (!is_null($detail)) {
                $detail->delete();
                return $this->sendResponse(204, "Detalle Eliminado Correctamente", ['detail' => 'El detalle fue eliminado correctamente']);
            } 
            return $this->sendResponse(400, "El detalle no existe", ['server_error' => 'Detalle no existe']);
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el Servidor", ['server_error' => $e->getMessage()]);
        }
    }
}
