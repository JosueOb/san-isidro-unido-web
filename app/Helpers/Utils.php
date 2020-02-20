<?php

namespace App\Helpers;

use App\User;
use App\SocialProfile;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Exception;
use App\Device;
// Exception

class Utils
{

    public static function getAppURL()
    {
        return env('APP_URL', 'http://localhost/github/sanisidrounido/public');
    }

    public static function array_keys_exists(array $keys, array $arr)
    {
        return !array_diff_key(array_flip($keys), $arr);
    }

    /**
     * Retorna un booleano si el array pasado cumple el formato de ubicacion
     * @param array $value
     *
     * @return boolean
     */
    public static function checkUbicationFormat($value)
    {
        $ubicationListFormat = ['latitude', 'longitude', 'address'];
        return self::array_keys_exists($ubicationListFormat, $value);
    }

    /**
     * Retorna el array de ubicaciones con la latitud y la longitud formateadas como números(double)
     * @param array $ubicationArray
     *
     * @return array
     */
    public function mapUbication($ubicationArray)
    {
        $ubicationArray['latitude'] = (gettype($ubicationArray['latitude']) === 'string') ? (float) $ubicationArray['latitude'] : $ubicationArray['latitude'];
        $ubicationArray['longitude'] = (gettype($ubicationArray['longitude']) === 'string') ? (float) $ubicationArray['longitude'] : $ubicationArray['longitude'];
        return $ubicationArray;
    }

    /**
     * Retornar el proveedor de una request, puede ser formulario o social
     * @param \Illuminate\Http\Request  $request
     *
     * @return string|null
     */
    public function getProviderRequest($request)
    {
        if ($request->filled("email") && $request->filled("password")) {
            return "formulario";
        }
        if ($request->filled("email") && $request->filled("social_id")) {
            return "social";
        }
        return null;
    }

    /**
     * Verifica si un usuario existe y retorna true/false
     * @param string  $email
     *
     * @return boolean
     */
    public function userExists($email)
    {
        $user = User::where('email', $email)->first();
        return (is_null($user)) ? false : true;
    }

    /**
     * Verifica si un usuario tiene perfiles sociales y retorna true/false
     * @param string  $provider
     * @param string  $email
     *
     * @return boolean
     */
    public function socialProfileExists($provider, $email)
    {

        $user = User::where('email', $email)->first();
        if (!is_null($user)) {
            $socialProfile = SocialProfile::where([
                ['user_id', '=', $user->id],
                ['provider', '=', $provider],
            ])->first();
            return (is_null($socialProfile)) ? false : true;
        } else {
            // throw new Exception('Usuario no existe');
            throw new \Exception("Usuario no existe", 500);
        }
    }

    /**
     * Verifica si una cedula es válida
     * @param string  $cedula
     *
     * @return boolean
     */
    public function verificarCedula($validarCedula) {
        $aux = 0;
        $par = 0;
        $impar = 0;
        $verifi;
        //Numeros Pares
        for ($i = 0; $i < 9; $i += 2) {
          $aux = 2 * (int)$validarCedula[$i];
          if ($aux > 9) {
            $aux -= 9;
          }
          $par += $aux;
        }
        //Numeros Impares
        for ($i = 1; $i < 9; $i += 2) {
          $impar += (int)$validarCedula[$i];
        }
        //Calcular numero auxiliar
        $aux = $par + $impar;
        //Calcular número de verificación
        if ($aux % 10 !== 0) {
          $verifi = 10 - ($aux % 10);
        } else {
          $verifi = 0;
        }
        //Verificar que numero verificacion coincida con el numero en la posicion 9
        if ($verifi === (int)$validarCedula[9]) {
          return true;
        } else {
          return false;
        }
      }


}
