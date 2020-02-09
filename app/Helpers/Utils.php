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
}
