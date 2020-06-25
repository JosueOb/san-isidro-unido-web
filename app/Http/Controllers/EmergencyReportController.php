<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EmergencyIsAttendedByPolice;
use App\Http\Middleware\EmergencyIsPublishedByModerator;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class EmergencyReportController extends Controller
{
    public function __construct()
    {
        //Se protege de publicar una emergencia en caso de que no haya sigo atendida por pun poliçía primero
        $this->middleware(EmergencyIsAttendedByPolice::class)-> only('publishEmergency');
        //Se protege de publicar nuevamente una emergencia
        $this->middleware(EmergencyIsPublishedByModerator::class)->only('publishEmergency');

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
        //Se obtiene la ubicación de la emergencia
        $ubication = json_decode($emergency->ubication, true);
        //Se obtiene las imágemes de la emergencia
        $images = $emergency->resources()->where('type', 'image')->get();
        //Se obtiene el usuario que reporto la emergencia
        $neighbor = $emergency->user;

        //Se obtiene información adicional del problema
        $additional_data = $emergency->additional_data;

        //se obtiene el usuario que haya aprobado o rechazado la petición
        $userWhoAttendedEmergency = $additional_data['status_attendance'] === 'atendido' ? User::find($additional_data['attended']['who']['id']) : null;
        $userWhoRechazedEmergency = $additional_data['status_attendance'] === 'rechazado' ? User::find($additional_data['rechazed']['who']['id']) : null;

        return view('emergency-reports.emergency', [
            'emergency' => $emergency,
            'ubication'=> $ubication,
            'images' => $images,
            'neighbor' => $neighbor,
            'userWhoAttendedEmergency' => $userWhoAttendedEmergency,
            'userWhoRechazedEmergency' => $userWhoRechazedEmergency,
            'additionalData' => $additional_data,
            'notification'=>$notification
        ]);
    }

    //Función para hacer pública la emergencia
    public function publishEmergency(Post $emergency, DatabaseNotification $notification){
         //Se cambia el estado del post, para que sea visible en la app
         $emergency->state = true;
         $emergency->save();
         
         return redirect()->route('emergencyReport.emergency',[
             'emergency'=>$emergency->id,
             'notification'=>$notification->id
         ])->with('success','Emergencia publicada');
    }
}
