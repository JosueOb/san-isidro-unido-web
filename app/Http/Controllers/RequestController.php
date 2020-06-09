<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;

class RequestController extends Controller
{
    //Se gestionan las solicitudes de problemas sociales y emergecias
    /*
    |--------------------------------------------------------------------------
    | Request Controller
    |--------------------------------------------------------------------------
    |
    | Se gestionan las solicitudes de problemas sociales y emergecias
    |
    */

    public function __construct()
    {
        
    }

    /**
     * Se presenta la socilitud de problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */

    public function showSocialProblem(Post $problem, DatabaseNotification $notification){
        //Se obtiene la ubicación del problema social
        $ubication = json_decode($problem->ubication, true);
        //Se obtiene las imágemes del problema social
        $images = $problem->resources()->where('type', 'image')->get();
        //Se obtiene el usuario que reporto el problema social
        $user = $problem->user;
        
        return view('request.socialProblem', [
            'problem' => $problem,
            'ubication'=> $ubication,
            'images' => $images,
            'user' => $user,
        ]);
    }

    /**
     * Se registra la aprobación del problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function approveSocialProblem(Post $problem){
        dd($problem);
    }
    /**
     * Se registra el rechazo del problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function rejectSocialProblem(Post $problem){
        dd($problem);
    }
}
