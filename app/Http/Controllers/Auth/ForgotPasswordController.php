<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request){
        $validData = $request->validate([
            'email'=>'required|email|exists:users,email',
        ],[
            'email.required'=> 'El campo :attribute es obligatorio',
            'email.email' => 'Ingrese un correo electrónico válido',
            'email.exists' => 'No se encuentra ningún usuario registrado con el correo electrónico ingresado'
        ]);

        $user = User::where('email', $validData['email'])->first();
        
        if($user->state && $user->getRol()){

            $this->broker()->sendResetLink(
                $this->credentials($request)
            );
            return back()->with('status', 'Se ha enviado un enlace a tu correo para restablecer la contraseña');

        }else{
            return back()->with('alert', 'Usuario no registrado');
        }
    }
}
