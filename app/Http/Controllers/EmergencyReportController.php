<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class EmergencyReportController extends Controller
{
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

    public function showEmergency(Post $emergency, DatabaseNotification $notification){
        //Se determina si la notificación no ha sido leída
        if($notification->unread()){
            //Se marca la notificación como leída
            $notification->markAsRead();
        }
        //Se obtiene la ubicación de la emergenci
        $ubication = json_decode($emergency->ubication, true);
        //Se obtiene las imágemes de la emergencia
        $images = $emergency->resources()->where('type', 'image')->get();
        //Se obtiene el usuario que reporto la emergencia
        $neighbor = $emergency->user;

        //Se obtiene información adicional del problema
        $additional_data = $emergency->additional_data;

        return view('emergency-reports.emergency', [
            'emergency' => $emergency,
            'ubication'=> $ubication,
            'images' => $images,
            'neighbor' => $neighbor,
            'additionalData' => $additional_data,
            // 'notification'=>$notification
        ]);
    }
}
