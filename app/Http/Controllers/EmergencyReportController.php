<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EmergencyIsAttendedByPolice;
use App\Http\Middleware\EmergencyIsPublishedByModerator;
use App\Http\Middleware\ProtectNotifications;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class EmergencyReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(ProtectNotifications::class)->only('show', 'publish');
        //Se protege de publicar una emergencia en caso de que no haya sigo atendida por pun poliçía primero
        $this->middleware(EmergencyIsAttendedByPolice::class)->only('publish');
        //Se protege de publicar nuevamente una emergencia
        $this->middleware(EmergencyIsPublishedByModerator::class)->only('publish');
    }

    /**
     * Se presenta la socilitud de problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */

    public function show(DatabaseNotification $notification)
    {
        //Se determina si la notificación no ha sido leída
        if ($notification->unread()) {
            //Se marca la notificación como leída
            $notification->markAsRead();
        }

        //Se obtiene información de la emergencia reportado como objeto Post
        $emergency = Post::findOrFail($notification->data['post']['id']);
        //Se obtiene la ubicación de la emergencia
        $ubication = $emergency->ubication;
        //Se obtiene las imágemes de la emergencia
        $images = $emergency->resources()->where('type', 'image')->get();
        //Se obtiene el estado de la emergencia
        $emergency_status_attendance = $emergency->additional_data['status_attendance'];
        //Se obtiene el usuario que reportó la emergencia
        $neighbor = $emergency->user;

        return view('emergency-reports.emergency', [
            'emergency' => $emergency,
            'ubication' => $ubication,
            'images' => $images,
            'neighbor' => $neighbor,
            'emergency_status_attendance' => $emergency_status_attendance,
            'notification' => $notification
        ]);
    }

    //Función para hacer pública la emergencia
    public function publish(DatabaseNotification $notification)
    {
        //Se obtiene información de la emergencia reportado como objeto Post
        $emergency = Post::findOrFail($notification->data['post']['id']);
        //Se cambia el estado del post, para que sea visible en la app
        $emergency->state = true;
        $emergency->save();

        return redirect()->route('emergencyReport.show', [
            'notification' => $notification->id
        ])->with('success', 'Emergencia publicada');
    }
}
