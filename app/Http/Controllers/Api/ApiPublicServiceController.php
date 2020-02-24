<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Api\ApiBaseController;
use App\PublicService;
use App\Subcategory;
use Exception;
use Spatie\QueryBuilder\QueryBuilder;

class ApiPublicServiceController extends ApiBaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/servicios-publicos",
     *     summary="Listado de servicios públicos",
     *     tags={"Servicios Públicos"},
     *   description="Obtener el listado de servicios públicos existentes",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de todos los servicios públicos."
     *     ),
     *     @OA\Response(response="default", description="Ha ocurrido un error.")
     * )
     * Retorna el listado de servicios públicos
     *
     * @return array
     */
    public function index()
    {
        try {
            $publicServices = PublicService::orderBy('id', 'desc')->with(['phones', 'subcategory'])->get();
            return $this->sendResponse(200, 'success', $publicServices);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/servicios-publicos/{id}",
     *     summary="Detalle de un servicio público",
     *     tags={"Servicios Públicos"},
     *   description="Obtener el detalle de un servicio público existente",
     *   @OA\Parameter(
     *         description="ID del servicio público a retornar",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle de un servicio público"
     *     ),
     *     @OA\Response(response="default", description="Ha ocurrido un error.")
     * )
     * Retorna el detalle de un servicio publico
     * @param int $id;
     *
     * @return array
     */
    public function detail($id)
    {
        try {
            $publicService = QueryBuilder::for(PublicService::class) 
                    ->findById($id)
                    ->orderBy('id', 'desc')
                    ->with(['phones', 'subcategory'])
                    ->first();
            

            if (!is_null($publicService)) {
                return $this->sendResponse(200, 'Recurso encontrado', $publicService);
            }
            return $this->sendError(404, 'No existe el servicio publico solicitado', [], []);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/servicios-publicos/categorias",
     *     summary="Listado de Categorias de los servicios públicos",
     *     tags={"Servicios Públicos"},
     *   description="Obtener el listado de categorias de los servicios públicos",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de Categorias de los servicios públicos."
     *     ),
     *     @OA\Response(response="default", description="Ha ocurrido un error.")
     * )
     * */
    public function getCategories()
    {

        try {
            $category = Category::slug('servicio-publico')->first();
            $publicServicesCategories = Subcategory::CategoryId($category->id)->get();
            return $this->sendResponse(200, 'Listado de Categorias', $publicServicesCategories);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/servicios-publicos/categoria/{slug}",
     *     summary="Servicios Públicos de una categoria",
     *     tags={"Servicios Públicos"},
     *   description="Obtener el listado de servicios públicos relacionados a la categoria solicitada",
     *   @OA\Parameter(
     *         description="Slug de la categoria de los servicios públicos a retornar",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *           format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de Servicios Públicos de una categoría en especifíco"
     *     ),
     *     @OA\Response(response="default", description="Ha ocurrido un error.")
     * )
     * Retorna los servicios publicos que pertenecen a una categoria en especifico
     * @param string $slug;
     *
     * @return array
     */
    public function filterByCategory($slug)
    {
        try {
            $subcategory = Subcategory::slug($slug)->first();
            if (is_null($subcategory)) {
                return $this->sendError(404, 'No existe la categoria solicitada', []);
            }
            $publicServices = PublicService::findByCategoryId($subcategory->id)
                ->with(['phones', 'subcategory'])
                ->get();
            return $this->sendResponse(200, 'Recurso encontrado', $publicServices);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

}
