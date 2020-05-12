<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Spatie\QueryBuilder\QueryBuilder;
use Exception;
use App\Http\Controllers\Api\ApiBaseController;
use App\Subcategory;

class ApiCategoryController extends ApiBaseController {
	
	/**
     * @OA\Get(
     *     path="/api/v1/categorias",
     *     summary="Listado de categorias",
     *     tags={"Categorias"},
     *   description="Obtener el listado de categorias",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de todas las categorias."
     *     ),
     *     @OA\Response(response="default", description="Ha ocurrido un error.")
     * )
	 * Retornar el listado de categorias
	 *
	 * @return array
	 */
	public function index() {
		try {
			$categories = Category::with(['subcategories'])
				->orderBy('id', 'desc')
				->simplePaginate(5);
			if (!is_null($categories)) {
				return $this->sendPaginateResponse(200, 'Listado de Categorias', $categories->toArray());
			}
			return $this->sendError(404, 'No existen categorias', ['category' => 'no existen categorias']);
		} catch (Exception $e) {
			return $this->sendError(500, 'Ocurrio un error en el servidor', ['server_error' => $e->getMessage()]);
		}
	}

	/**
     * @OA\Get(
     *     path="/api/v1/categorias/{id}",
     *     summary="Detalle de una categoría",
     *     tags={"Categorias"},
     *   description="Obtener el detalle de una categoría",
     *   @OA\Parameter(
     *         description="ID de la categoría a retornar",
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
     *         description="Detalle de una categoría"
     *     ),
     *     @OA\Response(response="default", description="Ha ocurrido un error.")
     * )
	 * Retornar el detalle de una categoría
	 * @param integer $id
	 *
	 * @return array
	 */
	public function detail($id) {
		try {
			$category = Category::findById($id)->first();
			if (!is_null($category)) {
				return $this->sendResponse(200, 'Detalle de Categorias', $category);
			}
			return $this->sendError(404, 'No existen la categoria solicitada', ['category' => 'no existen la categoria solicitada']);
		} catch (Exception $e) {
			return $this->sendError(500, 'Ocurrio un error en el servidor', ['server_error' => $e->getMessage()]);
		}
	}

	/**
     * @OA\Get(
     *     path="/api/v1/categorias/{slug}",
     *     summary="Categorias de acuerdo a su slug",
     *     tags={"Categorias"},
     *   description="Obtener el listado de categorias de acuerdo al slug",
     *   @OA\Parameter(
     *         description="Slug de la categoria a retornar",
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
     *         description="Listado de Categorias de acuerdo al slug solicitado"
     *     ),
     *     @OA\Response(response="default", description="Ha ocurrido un error.")
     * )
	 * Retornar el detalle de una categoria y permite incluir las subcategorias relacionadas
	 * @param string $slug
	 *
	 * @return array
	 */
	public function filterCategories($slug) {
		try {
            $categoriesFiltered = QueryBuilder::for(Category::class)
            ->slug($slug)
            ->allowedIncludes(['subcategories'])
            ->first();
			if (!is_null($categoriesFiltered)) {
				return $this->sendResponse(200, 'Subcategorias de la Categoria', $categoriesFiltered);
			} else {
				return $this->sendError(404, 'La categoria no existe', ['error' => 'categoria no existe']);
			}
		} catch (Exception $e) {
			return $this->sendError(500, 'Error en el Servidor', ['server_error' => $e->getMessage()]);
		}
    }
    
    /**
     * @OA\Get(
     *     path="/api/v1/categorias/{slug}/subcategorias",
     *     summary="Listado de Subcategorias de una categoria",
     *     tags={"Categorias"},
     *   description="Obtener el listado de subcategorias de una categoria de acuerdo al slug",
     *   @OA\Parameter(
     *         description="Slug de la categoria a retornar",
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
     *         description="Listado de Subcategorias de una categoria de acuerdo al slug solicitado"
     *     ),
     *     @OA\Response(response="default", description="Ha ocurrido un error.")
     * )
	 * Retornar las subcategorias de una categoria
	 * @param string $slug
	 *
	 * @return array
	 */
    public function getSubcategory($slugCategory){
        try{
            $category = Category::slug($slugCategory)->first();
            if(is_null($category)){
                return $this->sendError(400, 'La categoria que buscas no existe', ['category' => 'La categoria que buscas no existe']);
            }
            $subcategories= Subcategory::categoryId($category->id)->get();
            return $this->sendResponse(200, 'success', $subcategories);
        } catch (Exception $e) {
			return $this->sendError(500, 'Error en el Servidor', ['server_error' => $e->getMessage()]);
		}
    }
}
