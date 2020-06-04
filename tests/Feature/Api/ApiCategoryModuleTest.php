<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Category;
use App\Helpers\Utils;

class ApiCategoryModuleTest extends TestCase
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
    public function test_brings_all_categories()
    {
        $url = $this->baseUrl . '/categorias';
        try {
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            $this->assertEquals(200, $statusCode);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /* Probar traer el detalle de un servicio público*/
    function test_it_brings_detail_category()
    {
        $idTest = 120;
        $postOne = Category::select('id', 'name')->findById($idTest)->first();
        $url = $this->baseUrl . "/servicios-publicos/categoria/$idTest";
        try {
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
    public function test_brings_subcategories_of_categories()
    {
        try {
            $categoryNameTest = "problema";
            $category = Category::slug($categoryNameTest)->first();
            $url = $this->baseUrl . "/categorias/$categoryNameTest/subcategorias";
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            if(is_null($category)){
                $this->assertEquals(404, $statusCode);
            }else{
                $this->assertEquals(200, $statusCode);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
