<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NotificationController extends Controller
{
    public function api_problems(Request $request){
        $notifications = $request->user()->notifications;
        // dd($notifications);
        $problem_category = Category::where('slug', 'problema')->first();
        $problem_notifications = $notifications->filter(function($notification) use($problem_category){

            $notification_type = Arr::exists($notification->data, 'type') ? $notification->data['type'] : null;

            if($notification_type && $notification_type === 'problem_reported'){
                return $notification->data['post']['category_id'] === $problem_category->id;
            }
        }); 

        $unread_notifications = $problem_notifications->filter(function($notification){
            return $notification->unread();
        });


        return [
            'problem_notifications'=>array_values($problem_notifications->toArray()),//se re-indexa 
            'unread_notifications'=>array_values($unread_notifications->toArray()),//se re-indexa
        ];
        // return [
        //     'problem_notifications'=>array(),//se re-indexa 
        //     'unread_notifications'=>array(),//se re-indexa
        // ];
    }

    public function api_emergencies(Request $request){
        $notifications = $request->user()->notifications;
        // dd($notifications);
        $emergency_category = Category::where('slug', 'emergencia')->first();
        $emergency_notifications = $notifications->filter(function($notification) use($emergency_category){
            $notification_type = Arr::exists($notification->data, 'type') ? $notification->data['type'] : null;

            if($notification_type && $notification_type === 'emergency_reported'){
                return $notification->data['post']['category_id'] === $emergency_category->id;
            }

        });

        $unread_notifications = $emergency_notifications->filter(function($notification){
            return $notification->unread();
        });
        // dd($unread_notifications);

        return [
            'emergency_notifications'=>array_values($emergency_notifications->toArray()),//se re-indexa 
            'unread_notifications'=>array_values($unread_notifications->toArray()),//se re-indexa
        ];
    }

    public function api_memberships(Request $request){
        $notifications = $request->user()->notifications;

        $membership_notifications = $notifications->filter(function($notification) {

            $notification_type = Arr::exists($notification->data, 'type') ? $notification->data['type'] : null;

            if($notification_type && $notification_type === 'membership_reported'){
                return $notification;
            }

        });

        $unread_notifications = $membership_notifications->filter(function($notification){
            return $notification->unread();
        });

        return [
            'membership_notifications'=>array_values($membership_notifications->toArray()),//se re-indexa 
            'unread_notifications'=>array_values($unread_notifications->toArray()),//se re-indexa
        ];
    }

    //Se listan todas las notificaciones de problemas sociales reportados
    public function problems(Request $request){

        $notifications = $request->user()->notifications;
        $problem_category = Category::where('slug', 'problema')->first();

        $problem_notifications = $notifications->filter(function($notification) use($problem_category){

            $notification_type = Arr::exists($notification->data, 'type') ? $notification->data['type'] : null;

            if($notification_type && $notification_type === 'problem_reported'){
                return $notification->data['post']['category_id'] === $problem_category->id;
            }
        }); 

        return view('notifications.problem',[
            'all_problem_notifications'=>$problem_notifications,
        ]);
    }
    //Se listan todas las notificaciones de emergencias de problemas sociales reportados
    public function emergencies(Request $request){

        $notifications = $request->user()->notifications;

        $emergency_category = Category::where('slug', 'emergencia')->first();
        $emergency_notifications = $notifications->filter(function($notification) use($emergency_category){
            $notification_type = Arr::exists($notification->data, 'type') ? $notification->data['type'] : null;

            if($notification_type && $notification_type === 'emergency_reported'){
                return $notification->data['post']['category_id'] === $emergency_category->id;
            }

        });

        return view('notifications.emergency',[
            'all_emergency_notifications'=>$emergency_notifications,
        ]);
    }
    public function memberships(Request $request){
        $notifications = $request->user()->notifications;

        $membership_notifications = $notifications->filter(function($notification){
            $notification_type =  Arr::exists($notification->data, 'type') ? $notification->data['type'] : null;
            if($notification_type && $notification_type === 'membership_reported'){
                return $notification;
            }
        });

        return view('notifications.membership', [
            'all_membership_notifications'=>$membership_notifications,
        ]);

    }
}
