<?php

namespace App\Http\Controllers;

use App\{Membership, User};
use App\Helpers\OnesignalNotification;
use App\HelpersClass\ResponsibleMembership as HelperResponsibleMembership;
use App\Http\Middleware\MembershipIsAttendedByModerator;
use App\Http\Middleware\ProtectNotifications;
use App\Http\Requests\RejectReportRequest;
use App\Notifications\ApproveMembership;
use App\Notifications\MembershipRequest;
use App\Notifications\PublicationReport;
use App\Notifications\RejectMembership;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class MembershipController extends Controller
{
    public function __construct()
    {
        $this->middleware(ProtectNotifications::class)->only('show', 'approve', 'showReject', 'reject');
        $this->middleware(MembershipIsAttendedByModerator::class)->only('approve', 'showReject', 'reject');
    }

    public function show(DatabaseNotification $notification)
    {
        //Se determina si la notificación no ha sido leida
        if ($notification->unread()) {
            //Se marca a la notificación como leida
            $notification->markAsRead();
        }

        //Se obtiene información de la afiliación como objeto Membership
        $membership = Membership::findOrFail($notification->data['membership']['id']);

        //Se obtiene información del solicitante como objeto User
        $guest = User::findOrFail($notification->data['guest']['id']);
        // en caso de presnetar los datos de solicitante guardados en la notificación
        // $guest = $notification->data['guest'];

        return view('membership-reports.membership', [
            'notification' => $notification, //se envía el id de la notificación (string)
            'guest' => $guest, //se envía la información del solicitante (array)
            'membership' => $membership, //se envía información de la solicitud (array)
        ]);
    }

    public function approve(DatabaseNotification $notification, Request $request)
    {
        //Se obtiene información de la afiliación como objeto Membership para cambio de su estado y responsable
        $membership = Membership::findOrFail($notification->data['membership']['id']);
        //Se obtiene información del solicitante como objeto User para la asignación del rol
        $guest = User::findOrFail($notification->data['guest']['id']);

        //Se obtiene información del moderador que aceptó la solicitud
        $moderator = $request->user();

        $responsibleMembership = new HelperResponsibleMembership();
        $responsibleMembership->setApproved([
            'who' => $moderator, //morador que aprobó la solicitud
            'date' => now()->toDateTimeString(), //fecha de aprobación ejemplo del formato 1975-12-25 14:15:16
        ]);

        //Se cambia el estado de la membresía a aprobada y la información del morador que la aprobó
        $membership->status_attendance = 'aprobado';
        $membership->responsible = $responsibleMembership->getAll();
        $membership->save();

        // Se obtiene al rol de invitado y morador
        $guest_role = Role::where('slug', 'invitado')->first();
        $neighbor_role = Role::where('slug', 'morador')->first();

        //Se le asigna el rol de morador al solicitante
        $guest->roles()->attach([$neighbor_role->id], ['state' => true]);
        //Se le retira el rol de invitado
        // Detach a single role from the user...
        $guest->roles()->detach($guest_role->id);

        $n_title = 'Solicitud de afiliación aprobada';
        $n_description = 'Por favor, cierra sesión e ingresa nuevamente en la aplicación móvil, para usar las nuevas funcionalidades';
        $user_devices = OnesignalNotification::getUserDevices($guest->id);
        if (!is_null($user_devices) && count($user_devices) > 0) {
            
            OnesignalNotification::sendNotificationByPlayersID(
                $n_title,
                $n_description,
                ['action'=>'logout'],
                $user_devices
            );
            //Se notifica al solicitante la aprobación de su solicitud
            $guest->notify(new ApproveMembership());
            //Por cada morador activo, se notifica el reporte registrado
            $guest->notify(new MembershipRequest(
                'membership_approved', //tipo de la notificación
                $n_title, //título de la notificación
                $n_description, //descripcción de la notificación
                $membership, // solicitud de afiliación
                $guest //solicitante de la afiliación
            ));
        }

        return redirect()->route('membership.show', [
            'notification' => $notification->id
        ])->with('success', 'Solicitud de afiliación aprobada exitosamente');
    }

    public function showReject(DatabaseNotification $notification)
    {
        return view('membership-reports.showRejectMembership', [
            'notification' => $notification,
        ]);
    }

    public function reject(RejectReportRequest $request, DatabaseNotification $notification)
    {
        //Se valida al formulario de rechazo
        $validated = $request->validated();
        //Se obtiene información de la afiliación como objeto Membership para cambio de su estado y responsable
        $membership = Membership::findOrFail($notification->data['membership']['id']);
        //Se obtiene información del solicitante como objeto User para el envío de l notificación
        $guest = User::findOrFail($notification->data['guest']['id']);

        //Se obtiene información del moderador que rechazó la solicitud
        $moderator = $request->user();

        $responsibleMembership = new HelperResponsibleMembership();
        $responsibleMembership->setRechazed([
            'who' => $moderator, //morador que rechazó la solicitud
            'reason' => $validated['description'], //razón del rechazo
            'date' => now()->toDateTimeString(), //fecha de rechazo ejemplo del formato 1975-12-25 14:15:16
        ]);

        //Se cambia el estado de la membresía a aprobada y la información del morador que la aprobó
        $membership->status_attendance = 'rechazado';
        $membership->responsible = $responsibleMembership->getAll();
        $membership->save();

        $n_title = 'Solicitud de afiliación rechazada';
        $n_description = 'Tu solicitud ha sido rechazada por la siguiente razón: '. $validated['description'];
        $user_devices = OnesignalNotification::getUserDevices($guest->id);
        if (!is_null($user_devices) && count($user_devices) > 0) {
            
            OnesignalNotification::sendNotificationByPlayersID(
                $n_title,
                $n_description,
                null,
                $user_devices
            );
             //Se notifica al solicitante el rechazo de su solicitud
            $guest->notify(new RejectMembership($validated['description']));
            //Se nnotifica al solicitante de su rechazo en la afiliación
            $guest->notify(new MembershipRequest(
                'membership_rechazed', //tipo de la notificación
                $n_title, //título de la notificación
                $n_description, //descripcción de la notificación
                $membership, // solicitud de afiliación
                $guest //solicitante de la afiliación
            ));
       

        return redirect()->route('membership.show', [
            'notification' => $notification->id
        ])->with('success', 'Solicitud de afiliación rechazada exitosamente');
    }
}
