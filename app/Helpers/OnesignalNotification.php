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

class OnesignalNotification
{

    public static function getAppURL()
    {
        return env('APP_URL', 'http://localhost/github/sanisidrounido/public');
    }


    /**
     * Envia una petición POST a Onesignal para enviar una notificación PUSH y retorna el contenido de la respuesta
     * @param array  $body
     *
     * @return string
     */
    private static function sendPushNotification($body)
    {

        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $url = "https://onesignal.com/api/v1/notifications";
        $onesignalAppId = Config::get('siu_config.ONESIGNAL_APP_ID');
        $onesignalRestApiKey = Config::get('siu_config.ONESIGNAL_REST_API_KEY');
        $authorization = "Basic $onesignalRestApiKey";
        $headers = [
            'Authorization' => $authorization,
            'Content-Type' => "application/json"
        ];
        $body["app_id"] = $onesignalAppId;
        try {
            $request = $client->post($url, ['body' => json_encode($body), 'headers' => $headers]);
            return $request;
        } catch (RequestException $e) {
            echo 'Excepción capturada: ', Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                // echo Psr7\str($e->getResponse());
                throw new Exception($e->getResponse());
            }
        }
    }

    /**
     * Obtiene los dispositivos asociados a un usuario
     * @param int  $user_id
     *
     * @return array
     */
    public static function getUserDevices($user_id)
    {
        $devices = Device::findByUserId($user_id)->get();
        $id_devices = [];
        foreach($devices as $device){
            array_push($id_devices, $device->phone_id);
        }
        return $id_devices;
    }

    /**
     * Envia una notificacion con Onesignal a los dispositivos especificados en un array con sus ID`s
     * @param string  $title
     * @param string  $description
     * @param array  $aditionalData
     * @param array  $specificIDs
     *
     * @return array
     */
    public static function sendNotificationByPlayersID($title = '', $description = '', $aditionalData = [], $specificIDs = [])
    {
        $bodyPeticionOnesignal = [
            "data" => $aditionalData,
            "contents" => [
                "es" => $description,
                "en" => $description
            ],
            "headings" => [
                "en" => $title,
                "es" => $title,
            ],
            "include_player_ids" => $specificIDs,
        ];
        // if(!$aditionalData){
        //     $bodyPeticionOnesignal['data'] = (object)[];
        // }
        
        try {
            $request = self::sendPushNotification($bodyPeticionOnesignal);
            $response = $request->getBody();
            return ['content' => $response->getContents(), 'status' => $request->getStatusCode()];
        } catch (Exception $e) {
            // echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return $e->getMessage();
        }
    }

    /**
     * Envia una notificacion con Onesignal a todos los dispositivos subscriptos
     * @param string  $title
     * @param string  $description
     * @param array  $aditionalData
     * @param array  $segments
     *
     * @return array
     */
    public static function sendNotificationBySegments($title = '', $description = '', $aditionalData = null, $segments = ["All"])
    {
        $bodyPeticionOnesignal = [
            "included_segments" => $segments,
            "contents" => [
                "en" => $description,
                "es" => $description,
            ],
            "headings" => [
                "en" => $title,
                "es" => $title,
            ],
        ];
        if(!$aditionalData){
            $bodyPeticionOnesignal['data'] = (object)[];
        }
        try {
            $request= self::sendPushNotification($bodyPeticionOnesignal);
            $response = $request->getBody();
            return ['content' => $response->getContents(), 'status' => $request->getStatusCode()];
        } catch (Exception $e) {
            // echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return ['errors' => $e->getMessage(), 'status' => 500];
        }

    }
}
