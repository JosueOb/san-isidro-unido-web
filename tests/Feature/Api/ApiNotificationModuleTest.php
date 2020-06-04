<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Helpers\Utils;
use App\Helpers\OnesignalNotification;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Device;
// use App\Notifi

class ApiNotificationlModuleTest extends TestCase
{
    private $baseUrl;
    private $httpClient;

    /**
     * Setup Method
     *
     * @return void
     */
    protected function setUp(): void
    {

        $utils = new Utils();
        $this->baseUrl = $utils->getAppURL() . "/api/v1";
        $this->httpClient = new \GuzzleHttp\Client();
        parent::setUp();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_user_devices()
    {
        $user = User::orderBy(DB::raw('RAND()'))->take(1)->first();
        $devices = OnesignalNotification::getUserDevices($user->id);
        $this->assertEquals(200, 200);
    }

    public function test_send_multiple_user_devices_notification()
    {
        try {
            $response = OnesignalNotification::sendNotificationBySegments('Probar notificacion test', 'Contenido de la notificacion de prueba en Laravel');
            $this->assertEquals(200, $response['status']);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
