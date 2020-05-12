<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Helpers\Utils;

class ApiGeneralModuleTest extends TestCase
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
    public function test_not_found_route()
    {
        $url = $this->baseUrl . '/publicacis';
        try{
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            $this->assertEquals(404, $statusCode);
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    
}
