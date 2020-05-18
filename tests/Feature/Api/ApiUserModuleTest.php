<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\User;
use App\Helpers\Utils;

class ApiUserModuleTest extends TestCase
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
    public function test_brings_all_users()
    {
        try {
            $url = $this->baseUrl . '/usuarios';
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            $this->assertEquals(200, $statusCode);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_brings_all_roles_from_specific_user()
    {
        try {
            $idTest = 120;
            $url = $this->baseUrl . "/usuarios/$idTest/roles";
            $postOne = User::select('id', 'email')->findById($idTest)->first();
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            if (!is_null($postOne)) {
                $this->assertEquals(200, $statusCode);
            } else {
                $this->assertEquals(404, $statusCode);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /* Probar traer el detalle de un servicio público*/
    public function test_it_brings_detail_user()
    {
        try {
            $idTest = 120;
            $url = $this->baseUrl . "/usuarios/$idTest";
            $postOne = User::select('id', 'email')->findById($idTest)->first();
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            if (!is_null($postOne)) {
                $this->assertEquals(200, $statusCode);
            } else {
                $this->assertEquals(404, $statusCode);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_brings_all_devices_from_specific_user()
    {
        try {
            $idTest = 120;
            $url = $this->baseUrl . "/usuarios/$idTest/dispositivos";
            $postOne = User::select('id', 'email')->findById($idTest)->first();
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            if (!is_null($postOne)) {
                $this->assertEquals(200, $statusCode);
            } else {
                $this->assertEquals(404, $statusCode);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_brings_all_social_profiles_from_specific_user()
    {
        try {
            $idTest = 120;
            $url = $this->baseUrl . "/usuarios/$idTest/perfiles-sociales";
            $postOne = User::select('id', 'email')->findById($idTest)->first();
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            if (!is_null($postOne)) {
                $this->assertEquals(200, $statusCode);
            } else {
                $this->assertEquals(404, $statusCode);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_brings_all_emergencies_from_specific_user()
    {
        try {
            $idTest = 120;
            $url = $this->baseUrl . "/usuarios/$idTest/emergencias";
            $postOne = User::select('id', 'email')->findById($idTest)->first();
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            if (!is_null($postOne)) {
                $this->assertEquals(200, $statusCode);
            } else {
                $this->assertEquals(404, $statusCode);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_brings_all_notifications_from_specific_user()
    {
        try {
            $idTest = 120;
            $url = $this->baseUrl . "/usuarios/$idTest/notificaciones";
            $postOne = User::select('id', 'email')->findById($idTest)->first();
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            if (!is_null($postOne)) {
                $this->assertEquals(200, $statusCode);
            } else {
                $this->assertEquals(404, $statusCode);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    /**
    * Test Api Login
    *
    * @return void
    */
    public function test_login_user()
    {
        try {
            $body = [
                'email' => "jose@hotmail.com",
                'password' => "12345",
                'provider' => 'formulario'
            ];
            $url = $this->baseUrl . "/login";
            $statusCode = $this->httpClient->request('POST', $url, [
                'http_errors' => false,
                'json' => $body
                ])->getStatusCode();
            $this->assertEquals(200, $statusCode);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
    * Test Api Register
    *
    * @return void
    */
    public function test_register_fails_user()
    {
        try {
            $body = [
                
                    "first_name" => "JavierB1",
                    "last_name" => "B1Play",
                    "email" => "by_tosh21@hotmail.com",
                    "provider" => "facebook",
                    "password" => "12345",
                    "social_id" => "54as8s8a7s8a7sas",
                    "device" => [
                        "description" => "javier1device_android",
                        "phone_id" => "11278272KM",
                        "phone_models" => "Samsung J1 Ace"
                    ]               
            ];
            $url = $this->baseUrl . "/registro";
            $response = $this->httpClient->request('POST', $url, [
                'http_errors' => false,
                'json' => $body
            ]);
            $body = $response->getBody()->getContents();
            $statusCode = $response->getStatusCode();
            // dd($body, $statusCode);
            $this->assertEquals(400, $statusCode);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
