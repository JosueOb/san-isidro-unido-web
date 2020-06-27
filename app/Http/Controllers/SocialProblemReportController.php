<?php

namespace App\Http\Controllers;

use App\HelpersClass\AdditionalData;
use App\Http\Middleware\ProblemIsAttendedByModerator;
use App\Http\Middleware\ProtectNotifications;
use App\Http\Requests\RejectReportRequest;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class SocialProblemReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(ProtectNotifications::class)->only('show', 'approve', 'showReject', 'reject');
        $this->middleware(ProblemIsAttendedByModerator::class)->only('approve', 'showReject', 'reject');
    }

    /**
     * Se presenta la socilitud del problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */

    public function show(DatabaseNotification $notification)
    {
        // Se determina si la notificación no ha sido leída
        if ($notification->unread()) {
            //Se marca la notificación como leída
            $notification->markAsRead();
        }

        //Se obtiene información del problema social reportado como objeto Post
        $social_problem = Post::findOrFail($notification->data['post']['id']);
        //Se obtiene la ubicación del problema social
        $ubication = $social_problem->ubication;
        //Se obtiene las imagenes del problema social
        $images = $social_problem->resources()->where('type', 'image')->get();
        //Se obtiene el estado del problema social
        $social_problem_status_attendance = $social_problem->additional_data['status_attendance'];
        //Se obtiene información del morador que reportó el problema social como objeto User
        $neighbor = User::findOrFail($notification->data['neighbor']['id']);

        return view('social-problem-reports.socialProblem', [
            'social_problem' => $social_problem,
            'ubication' => $ubication,
            'images' => $images,
            'neighbor' => $neighbor,
            'social_problem_status_attendance' => $social_problem_status_attendance,
            'notification' => $notification
        ]);
    }

    /**
     * Se registra la aprobación del problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function approve(DatabaseNotification $notification, Request $request)
    {
        //Se obtiene información del problema social reportado como objeto Post
        $social_problem = Post::findOrFail($notification->data['post']['id']);
        //Se obtiene información del moderador que aprobó el reporte de problema social
        $moderator = $request->user();

        //Datos aprobación del problema social
        $approval = new AdditionalData();
        $approval->setInfoSocialProblem([
            "approved" => [
                'who' => $moderator,
                'date' => now()->toDateTimeString(),
            ],
            "status_attendance" => 'aprobado'
        ]);

        //Se actualiza el registro del problema social, con los datos de aprobación
        $social_problem->additional_data = $approval->getInfoSocialProblem();
        //Se cambia el estado del post, para que sea visible en la app
        $social_problem->state = true;
        $social_problem->save();

        return redirect()->route('socialProblemReport.show', [
            'notification' => $notification->id
        ])->with('success', 'Problema social aprobado exitosamente');
    }
    /**
     * Se presenta el formulario de rechazo de problema social
     *
     * @return \Illuminate\Http\Response
     */
    public function showReject(DatabaseNotification $notification)
    {
        return view('social-problem-reports.showRejectSocialProblem', [
            'notification' => $notification
        ]);
    }
    /**
     * Se registra el rechazo del problema social
     *
     * @param  Post  $problem
     * @param  DatabaseNotification  $notification
     * @return \Illuminate\Http\Response
     */
    public function reject(RejectReportRequest $request, DatabaseNotification $notification)
    {
        $validated = $request->validated();

        //Se obtiene información del problema social reportado como objeto Post
        $social_problem = Post::findOrFail($notification->data['post']['id']);
        //Se obtiene información del moderador que aprobó el reporte de problema social
        $moderator = $request->user();

        //Datos aprobación del problema social
        $approval = new AdditionalData();
        $approval->setInfoSocialProblem([
            "rechazed" => [
                'who' => $moderator, //usuario que rechazó el problema social
                'reason' => $validated['description'], //razón del rechazo del problema social
                'date' => now()->toDateTimeString(), //fecha de rechado
            ],
            "status_attendance" => 'rechazado'
        ]);

        //Se actualiza el registro del problema social, con los datos de rechazo
        $social_problem->additional_data = $approval->getInfoSocialProblem();
        $social_problem->save();

        return redirect()->route('socialProblemReport.show', [
            'notification' => $notification->id
        ])->with('success', 'Problema social rechazado exitosamente');
    }
}
