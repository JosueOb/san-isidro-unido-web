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

    //Se sobrescribe el método sendResetLinkEmail con la finalidad de verificar que el usuario 
    // se encuentre activo y con un rol diferente a invitado para el envío del enlace de restablecimiento
    // de contraseña
    public function sendResetLinkEmail(Request $request){

        //Se valida el campo email
        $validData = $request->validate([
            'email'=>'required|email|exists:users,email',
        ],[
            'email.required'=> 'El campo :attribute es obligatorio',
            'email.email' => 'Ingrese un correo electrónico válido',
            'email.exists' => 'No se encuentra ningún usuario registrado con el correo electrónico ingresado'
        ]);
        //Se obtiene al usuario perteneciente al correo electrónico ingresado
        $user = User::where('email', $validData['email'])->first();

        //Se envía el enlace si el usuario tiene algún rol del sistema web activo
        if($user->hasSomeActiveWebSystemRole()){

            $this->broker()->sendResetLink(
                $this->credentials($request)
            );
            return back()->with('status', 'Se ha enviado un enlace a tu correo electrónico para restablecer tu contraseña');

        }else{
            return back()->with('alert', 'Usuario no registrado');
        }
    }
}
