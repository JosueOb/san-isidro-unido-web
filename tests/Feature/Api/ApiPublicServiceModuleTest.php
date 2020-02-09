<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\PublicService;
use App\Helpers\Utils;

class ApiPublicServiceModuleTest extends TestCase
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
    public function tests_bring_all_public_services_avalaible()
    {
       $url = $this->baseUrl . '/servicios-publicos';
        try{
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            // dd($url);
            // dd($statusCode);
            $this->assertEquals(200, $statusCode);
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

     /* Probar traer el detalle de un servicio público*/
     function test_it_brings_detail_public_service()
     {
         $idTest = 120;
         $postOne = PublicService::findById($idTest)->select('id','name')->first();
         $url = $this->baseUrl . "/servicios-publicos/$idTest";
         $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
         if(!is_null($postOne)){
             $this->assertEquals(200, $statusCode);
         }else{
             $this->assertEquals(404, $statusCode);
         }
     }

     /* Probar traer las categorias de los servicios públicos*/
     function test_it_brings_categories_of_public_services()
     {
         $url = $this->baseUrl . "/servicios-publicos/categorias";
         $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
        $this->assertEquals(200, $statusCode);
     }
}
