<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class MembershipController extends Controller
{
    //
    public function showMembership( User $user, DatabaseNotification $notitication){

        //Se determina si la notificación no ha sifo leida
        if($notitication->unread()){
            //Se marca a la notificación como leida
            $notitication->markAsRead();
        }
        
        //se obtiene el usaria que está realizando la solicitu de afiliación
        $user_request = $user;
        // dd($user_request->getFullName());

        return view('membership-reports.membership', [
            'guest'=> $user,
        ]);

    }
}
