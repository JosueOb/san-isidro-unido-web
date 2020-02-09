<?php

namespace Tests\Feature\Api;

// use PhpUnit\Framework\TestCase;
use Tests\TestCase;
use App\Post;
use App\Helpers\Utils;

class ApiPostModuleTest extends TestCase
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


    /* Probar traer todas las publicaciones*/
    function test_it_brings_all_publications()
    {
        $url = $this->baseUrl . '/publicaciones';
        try {
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            $this->assertEquals(200, $statusCode);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    /* Probar traer el detalle de una publicacion*/
    function test_it_brings_detail_publication()
    {
        $idTest = 10;
        $postOne = Post::select('id', 'title')->findById($idTest)->first();
        $url = $this->baseUrl . "/publicaciones/$idTest";
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

    /* Probar traer posts frilteados por categoria*/
    function test_it_filter_posts_by_category()
    {
        $url = $this->baseUrl . '/publicaciones?filter[category]=emergencias';
        try {
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            $this->assertEquals(200, $statusCode);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /* Probar traer posts filtrados por categoria y subcategoria que existen*/
    function test_it_filter_posts_by_category_and_subcategory()
    {
        $url = $this->baseUrl . '/publicaciones?filter[category]=problemas_sociales&filter[subcategory]=seguridad';
        try {
            $statusCode = $this->httpClient->request('GET', $url, ['http_errors' => false])->getStatusCode();
            $this->assertEquals(200, $statusCode);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /* Probar traer posts filtrados por categoria y subcategoria que existen*/
    function test_it_filter_posts_by_category_and_subcategory_not_exists()
    {
        $url = $this->baseUrl . '/publicaciones?filter[category]=emergencias&filter[subcategory]=seguridad';
        try {
            $response = $this->httpClient->request('GET', $url, ['http_errors' => false]);
            $body = $response->getBody()->getContents();
            $api_response = json_decode($body);
            $this->assertEquals(0, count($api_response->data));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
