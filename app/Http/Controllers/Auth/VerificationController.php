<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth')->only('show','resend');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        //Se obtiene el id de la URL
        $userIdRequest = $request->route('id');
        //Se verifica si a iniciado sessión
        if($request->user()){
            //en caso de que exista sessión y no coincida coindica el id del correo con el de la sessión
            if ($userIdRequest != $request->user()->getKey()) {
                throw new AuthorizationException();
            }
            //Se verifica si el usuario tiene el correo verificado
            if ($request->user()->hasVerifiedEmail()) {
                return redirect($this->redirectPath());
            }
            //Se marca como correo verificado del usuario
            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }
            return redirect($this->redirectPath())->with('verified', true);
        }else{
            //se debe verificar que el id de la URL que se recibe exista
            $searchUser = User::findOrFail($userIdRequest);
            //Se verifica si el campo de correo verificado sea null para marcarlo como verificado
            //, caso contrario se redirecciona a la vista login
            if(is_null($searchUser->email_verified_at)){
                $searchUser->email_verified_at = now();
                $searchUser->save();
            }

            if($searchUser->hasSomeActiveWebSystemRole()){
                return redirect()->route('login');
            }else{
                $temporaryURL = URL::temporarySignedRoute(
                    'verifiedMail', now()->addMinutes(15),
                    ['id' => $searchUser->id]);
                // return 
                return redirect($temporaryURL);
            }

        }

    }
}
