<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    //Se obrescribe el método validationErrorMessage para traducir los mensajes de errores
    //de validación del request
    protected function validationErrorMessages()
    {
        return [
            'toke.required'=>'El :attribute es obligatorio',

            'email.required' => 'El campo :attribute es obligatorio',
            'email.email' => 'El correo ingresado no es válido',


            'password.required'=>'El campo :attribute es obligatorio',
            'password.min'=>'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed'=>'Las contraseñas ingresadas no coinciden',
        ];
    }
    //Se sobrescribe el método sendResetFailResponse para obtener la respuesta en caso de que 
    //se prensente un incoveniente, traducir su mensaje de error y presentarlo al usuario
    protected function sendResetFailedResponse(Request $request, $response)
    {
        $message = '';

        switch ($response) {
            case Password::INVALID_USER:
                $message = 'No se encuentra ningún usuario registrado con el correo electrónico ingresado';
                break;
            case Password::INVALID_PASSWORD:
                $message = 'Las contraseñas deben tener al menos ocho caracteres y coincidir';
                break;
            case Password::INVALID_TOKEN:
                $message = 'Este token de restablecimiento de contraseña no es válido';
                break;
        }
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
        
    }
    //Se sobrescribe el método sendResetResponse para traducir el mensaje de éxito al usuario
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())
                            ->with('status', 'Ha restablecido su contraseña con exito');
    }
}
