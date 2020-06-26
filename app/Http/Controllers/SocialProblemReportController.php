<?php

namespace App\Http\Controllers;

use App\HelpersClass\AdditionalData;
use App\Http\Middleware\ProblemIsAttendedByModerator;
use App\Http\Middleware\ProtectSocialProblemReportNotification;
use App\Http\Middleware\SocialProblemReport;
use App\Http\Middleware\SocialProblemRequest;
use App\Http\Requests\RejectReportRequest;
use App\Http\Requests\RejectSocialProblemRequest;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class SocialProblemReportController extends Controller
{
    //Se gestionan las solicitudes de problemas sociales 
    /*
    |--------------------------------------------------------------------------
    | SOCIAL PROBLEMA REPORT
    |--------------------------------------------------------------------------
    |
    | Se gestionan las solicitudes de problemas sociales y emergecias
    |
    */

    public function __construct()
    {
        //Se verifica si tiene el usuario el rol con el permiso de aceptar o rechazar las solicitudes de problemas sociales
        $this->middleware('can:socialProblemReports.approveOReject')->only('approveSocialProblem', 'showRejectSocialProblem', 'rejectSocialProblem');
        //Se verifica que el id de la notificación le pertenece al usuario que realiza la petición http
        $this->middleware(ProtectSocialProblemReportNotification::class)->only('showSocialProblem','approveSocialProblem', 'showRejectSocialProblem', 'rejectSocialProblem');
        $this->middleware(ProblemIsAttendedByModerator::class)->only('approveSocialProblem', 'showRejectSocialProblem', 'rejectSocialProblem');
    }

    /**
     * Se presenta la socilitud de problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */

    public function showSocialProblem(Post $problem, DatabaseNotification $notification){
        //Se determina si la notificación no ha sido leída
        if($notification->unread()){
            //Se marca la notificación como leída
            $notification->markAsRead();
        }
        //Se obtiene la ubicación del problema social
        $ubication = json_decode($problem->ubication, true);
        //Se obtiene las imágemes del problema social
        $images = $problem->resources()->where('type', 'image')->get();
        //Se obtiene el usuario que reporto el problema social
        $neighbor = $problem->user;

        //Se obtiene información adicional del problema
        $additional_data = $problem->additional_data;

        //se obtiene el usuario que haya aprobado o rechazado la petición
        $userWhoApprovedProblem = $additional_data['status_attendance'] === 'aprobado' ? User::find($additional_data['approved']['who']['id']) : null;
        $userWhoRechazedProblem = $additional_data['status_attendance'] === 'rechazado' ? User::find($additional_data['rechazed']['who']['id']) : null;

        return view('social-problem-reports.socialProblem', [
            'problem' => $problem,
            'ubication'=> $ubication,
            'images' => $images,
            'neighbor' => $neighbor,
            'additionalData' => $additional_data,
            'userWhoApprovedProblem' => $userWhoApprovedProblem,
            'userWhoRechazedProblem' => $userWhoRechazedProblem,
            'notification'=>$notification
        ]);
    }

    /**
     * Se registra la aprobación del problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function approveSocialProblem(Post $problem, DatabaseNotification $notification, Request $request){
        //Se obtiene el usuario que aprobó el problema
        $user = $request->user();

        $additionalData = new AdditionalData();
        $additionalData->setInfoSocialProblem([
            "approved" => [
                'who'=>$user,
                'date'=>now(),
            ],
            "status_attendance" => 'aprobado'
        ]);

        //Se actualiza el estado de la solicitud de problema social
        $problem->additional_data = $additionalData->getInfoSocialProblem();
        //Se cambia el estado del post, para que sea visible en la app
        $problem->state = true;
        $problem->save();
        
        // return redirect()->back()->with('success','Problema social aprobado');
        return redirect()->route('socialProblemReport.socialProblem',[
            'problem'=>$problem->id,
            'notification'=>$notification->id
        ])->with('success','Problema social aprobado');
    }
    /**
     * Se presenta el formulario de rechazo de problema social
     *
     * @return \Illuminate\Http\Response
     */
    public function showRejectSocialProblem(Post $problem, DatabaseNotification $notification){
        return view('social-problem-reports.showRejectSocialProblem', [
            'problem'=>$problem,
            'notification'=>$notification
        ]);
    }
    /**
     * Se registra el rechazo del problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function rejectSocialProblem(RejectReportRequest $request, Post $problem, DatabaseNotification $notification){
        //Se valida la razón del rechazo del problema social (reglas de validación)
        $validated = $request->validated();

        // Se obtiene el usuario que rechazó en problema social
        $user = $request->user();

        $additionalData = new AdditionalData();
        $additionalData->setInfoSocialProblem([
            "rechazed"=>[
                'who'=>$user,
                'reason'=>$validated['description'],
                'date'=>now(),
            ],
            "status_attendance" => 'rechazado'
        ]);

        //Se actualiza el estado de la solicitud de problema social
        $problem->additional_data = $additionalData->getInfoSocialProblem();
        $problem->save();
        
        return redirect()->route('socialProblemReport.socialProblem',[
            'problem'=>$problem->id,
            'notification'=>$notification->id
        ])->with('danger','Problema social rechazado');

    }
}
