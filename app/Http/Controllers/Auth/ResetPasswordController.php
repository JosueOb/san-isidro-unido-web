<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\URL;

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
    protected $userHasSomeActiveWebSystemRole = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|max:100|same:password_confirmation|regex:/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,100}$/',
            'password_confirmation' => 'required',
        ];
    }
    //Se obrescribe el método validationErrorMessage para traducir los mensajes de errores
    //de validación del request
    protected function validationErrorMessages()
    {
        return [
            'toke.required' => 'El :attribute es obligatorio',

            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico ingresado no es válido',

            'password.required' => 'El campo contraseña es obligatorio',
            'password.same' => 'Las contraseñas ingresadas no coinciden',
            'password.min' => 'La contraseña debe contener al menos a 8 caracteres',
            'password.max' => 'La contraseña no debe ser mayor a 100 caracteres',
            'password.regex' => 'La contraseña ingresada no es segura',

            'password_confirmation' => 'El campo confirmación de contraseña es obligatorio',
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
                $message = 'La contraseña debe tener como mínimo 8 caracteres, al menos un dígito y una mayúscula, y coincidir';
                break;
            case Password::INVALID_TOKEN:
                $message = 'Este token de restablecimiento de contraseña no es válido';
                break;
        }
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->save();

        event(new PasswordReset($user));

        if ($user->hasSomeActiveWebSystemRole()) {
            $this->userHasSomeActiveWebSystemRole = true;
            $this->guard()->login($user);
        }
    }
    //Se sobrescribe el método sendResetResponse para traducir el mensaje de éxito al usuario
    protected function sendResetResponse(Request $request, $response)
    {
        if ($this->userHasSomeActiveWebSystemRole) {
            return redirect($this->redirectPath())
                ->with('status', 'Ha restablecido su contraseña con éxito');
        } else {
            $temporaryURL = URL::temporarySignedRoute(
                'passwordChanged', //nombre de la ruta route()->name()
                now()->addMinutes(15) //duración del enlace
            );
            // return 
            return redirect($temporaryURL);
        }
    }
}
