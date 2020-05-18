<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiUserNotRegister
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $email = $request->email;
        $provider = $request->provider;   
        $providerKey = ($provider == 'formulario') ? 'password' : 'social_id';
        $providerValue = $request->input($providerKey, '');
        //Verificar datos necesarios
		if (!$email) {
            return $this->sendInvalidResponse(400, 'Email Requerido', ['email'=>'Email Requerido']);
        } 
		if (!$provider) {
            return $this->sendInvalidResponse(400, 'Proveedor Requerido', ['provider'=>'Proveedor Requerido']);
        } 
        //Añadir proveedor type y value
        $request->request->add(['providerKey' => $providerKey]);
        $request->request->add(['providerValue' => $providerValue]);
        //Verificar existe usuario
        $userExist = User::where('email', $email)->first();
        //Verificar por tipo de proveedor
        if($provider == 'formulario'){
            $request->request->add(['user_exists' => ($userExist) ? true: false]);
        }else{
            if(!$userExist){
                $request->request->add(['user_exists' => false]);
            }else{
                $userSocial = $userExist->social_profiles()->provider($provider)->socialId($providerValue)->first();
                if($userSocial){
                    $request->request->add(['user_exists' => true]);
                }
                else{
                    $request->request->add(['user_exists' => false]);
                } 
            }
        }
		return $next($request);
    }

    public function sendInvalidResponse($code=400, $message = "Petición Inválida", $errors=[]){
		return response()->json([
			"message" => $message,
			"errors" => $errors,
		], $code);
	}
}
