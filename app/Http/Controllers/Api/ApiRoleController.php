<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiBaseController;
use Spatie\QueryBuilder\QueryBuilder;
use App\Role;
use Exception;

class ApiRoleController extends ApiBaseController
{
  
    /**
     * Retorna el listado de roles de la Aplicación
     *
     * @return array
     */
    public function index() {
        try {
            $roles = Role::orderBy('id', 'desc')->get();
           
            return $this->sendResponse(200, 'success', $roles);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Retorna el detalle de un rol
     * @param integer $id
     *
     * @return array
     */
    public function detail($id) {
        try {
            // $rol = Role::findById($id)->with(['users'])
            //     ->first();
                $rolesFiltered = QueryBuilder::for(Role::class)
                ->allowedIncludes(['users'])
                ->first();
            //Verificar si el rol existe
            // if (!is_null($rol)) {
            return $this->sendResponse(200, 'success', $rolesFiltered);
            // }
            return $this->sendError(404, 'No existe el rol', ['rol' => 'No existe el rol']);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }
}
