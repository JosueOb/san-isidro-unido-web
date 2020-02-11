<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Resource;
use App\Helpers\Utils;

class ApiImageModuleTest extends TestCase
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
    public function tests_bring_image()
    {
        try{
            $image = Resource::inRandomOrder()->first();
            $url = $this->baseUrl . "/imagenes/$image->url";
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            // var_dump($image->url);
            if (filter_var($image->url, FILTER_VALIDATE_URL) === FALSE) {
                $this->assertEquals(200, $statusCode);
            }else{
                $this->assertEquals(404, $statusCode);
            }
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
