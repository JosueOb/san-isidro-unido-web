<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\ApiBaseController;
use App\PublicService;
use App\Subcategory;
use App\Category;
use Spatie\QueryBuilder\QueryBuilder;
use Exception;

class ApiPublicServiceController extends ApiBaseController {

    /**
     * Retorna el listado de servicios públicos
     *
     * @return array
     */
    public function index() {
        try {
            $publicServices = PublicService::orderBy('id', 'desc')->with(['phones', 'subcategory'])->get();
            return $this->sendResponse(200, 'success', $publicServices);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }
    
    

    /**
     * Retorna el detalle de un servicio publico
     * @param int $id;
     * 
     * @return array
     */
    public function detail($id) {
        try {
            $publicService = QueryBuilder::for(PublicService::class)
            ->findById($id)
            ->orderBy('id', 'desc')
            ->with(['phones', 'subcategory'])
            ->first();
            if(!is_null($publicService)){
                return  $this->sendResponse(200, 'Recurso encontrado', $publicService);
            }
            return $this->sendError(404, 'No existe el servicio publico solicitado',[], []);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    public function getCategories(){
        
        try {
            $category = Category::slug('servicio-publico')->first();
            $publicServicesCategories = Subcategory::CategoryId($category->id)->get();
            return  $this->sendResponse(200, 'Listado de Categorias', $publicServicesCategories);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Retorna los servicios publicos que pertenecen a una categoria en especifico
     * @param string $slug;
     * 
     * @return array
     */
    public function filterByCategory($slug) {
        try {
            $subcategory = Subcategory::slug($slug)->first();
            if(is_null($subcategory)){
                return $this->sendError(404, 'No existe la categoria solicitada', []);
            }
            $publicServices = PublicService::findByCategoryId($subcategory->id)
            ->with(['phones', 'subcategory'])
            ->get();
            return  $this->sendResponse(200, 'Recurso encontrado', $publicServices);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }
    
}
