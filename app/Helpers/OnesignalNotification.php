<?php

namespace App\Helpers;

use App\User;
use App\SocialProfile;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Exception;
use App\Device;

class OnesignalNotification
{

    private static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public static function generateUniqueId(){
        // return self::generateRandomString(8). "-" . self::generateRandomString(4) . "-" . self::generateRandomString(4) . "-" . self::generateRandomString(4) . "-" . self::generateRandomString(12);
        return "";
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
            dd("sendPushNotification ERROR", $e);
            if ($e->hasResponse()) {
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
            if($device->phone_id && $device->phone_id != ''){
                array_push($id_devices, $device->phone_id);
            }
        }
        dd($id_devices);
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
        
        try {
            $playerIdFake = self::generateUniqueId();
            dd($bodyPeticionOnesignal, $playerIdFake);
            $request = self::sendPushNotification($bodyPeticionOnesignal);
            $response = $request->getBody();
            return ['content' => $response->getContents(), 'status' => $request->getStatusCode()];
        } catch (Exception $e) {
            dd("sendNotificationByPlayersID ERROR", $e);
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
        }else{
            $bodyPeticionOnesignal['data'] = $aditionalData;
        }
        try {
            $request= self::sendPushNotification($bodyPeticionOnesignal);
            $response = $request->getBody();
            return ['content' => $response->getContents(), 'status' => $request->getStatusCode()];
        } catch (Exception $e) {
            return ['errors' => $e->getMessage(), 'status' => 500];
        }

    }
}
