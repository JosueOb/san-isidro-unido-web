<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
    
    public function login(Request $request){
        // Se establecen las reglas de validación y los mensajes 
        //a mostrar cuando una regla no se cumpla una determinada regla
        $validData = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:4'
        ],[
            'email.required'=>'El campo :attribute es obligatorio',
            'email.email'=>'Ingrese un correo electrónico válido',
            'password.required'=>'El campo :attribute es obligatorio',
            'password.min'=>'El campo :attribute debe ser mayor a :min caracteres'
        ]);

        //Se obtiene el valor true (si a sido seleccionado) o false(no ha sido seleccionado)
        //el checkbox remember
        $remember = $request->filled('remember');

        //Se obtiene al usuario con el correo eléctronico ingresado
        $verifyUserEmail = User::where('email', $validData['email'])->first();

        //Se verifica si existe un usuario con el correo ingresado y que su estado sea activo
        if($verifyUserEmail && $verifyUserEmail->state){
            //Se verifica que las credenciales ingresadas concuerden con el registro de la BDD,
            //el segundo atributo $remember permitirá recordar el token de la sesión en caso de
            //que sea true
            if(Auth::attempt($validData, $remember)){
                return redirect('home')->with('status', 'Ingreso exitosamente');
            }else{
                return redirect('login')->with('info', 'Las credenciales no son las correctas');
            }
        }else{
            return redirect('login')->with('info', 'Usuario no registrado');
        }
    }
}
