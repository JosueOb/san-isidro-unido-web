<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiBaseController;
use App\MobileNotification;
use App\User;
use Exception;
use Illuminate\Http\Request;

class ApiMobileNotificationController extends ApiBaseController
{

    /**
     * Retorna las notificaciones de un usuario
     * @param integer $user_id
     *
     * @return array
     */
    public function getNotificationsUser(Request $request, $user_id) {
        try {
            //Verificar si existe el usuario
            $user = User::findById($user_id)->first();
            $filterSize =  ($request->get('size')) ? intval($request->get('size')): 20;
            if (is_null($user)) {
                return $this->sendError(404, 'no existe el usuario', ['notifications' => 'no existe el usuario']);
            }
            $notifications = $user->notifications()->simplePaginate($filterSize)->toArray();
            return $this->sendPaginateResponse(200, 'Notificaciones obtenidas correctamente', $notifications);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }
}
