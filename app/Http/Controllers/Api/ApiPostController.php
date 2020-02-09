<?php

namespace App\Http\Controllers\Api;

use Caffeinated\Shinobi\Models\Role;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Category;
use App\Detail;
use App\Helpers\ApiImages;
use App\Http\Controllers\Api\ApiBaseController;
use App\Image;
use App\Post;
use App\User;
use App\Notifications\PostNotification;
use App\Rules\Api\Base64FormatImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Helpers\OnesignalNotification;

class ApiPostController extends ApiBaseController
{
     /**
     * Constructor Clase
     *
     * @return void
     */
    public function __construct()
    {
        $this->categories = Config::get('siu_config.categorias');
        $this->ubicationErrors = [
            'ubication.latitude.regex' => 'El campo :attribute debe contener una latitud válida',
            'ubication.longitude.regex' => 'El campo :attribute debe contener una longitud válida',
            'ubication.address.regex' => 'El campo :attribute debe contener solo letras y números',
            'ubication.description.regex' => 'El campo :attribute debe contener solo letras y números'
        ];
    }

   /**
     * Filtra las publicaciones por categoria, subcategoria o titulo y permite paginación
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     *
     * @return array
     */
    public function index(Request $request) {

        if($request->get('category') && !Category::slug($request->get('category'))->first()){
            dd('No existe categoria');
        }else{
            dd('Existe categoria');
            
        }

        //Retornar Paginacion
        $posts = Post::simplePaginate(10)->toArray();
        return $this->sendPaginateResponse(200, 'Publicaciones obtenidas correctamente', $posts);

        
        try {
            $allowPaginate = ($request->get('pagination'))?true:false;
            $sortAllowed = ['ASC', 'DESC'];

            $sort_direction = ($request->get('sort') && in_array(strtoupper($request->get('sort')), $sortAllowed)) ? strtoupper($request->get('sort')): 'DESC'; 


            $queryBuilder = QueryBuilder::for(Post::class)
                ->join('categories', 'categories.id', '=', 'posts.category_id')
                ->leftJoin('subcategories', 'subcategories.id', '=', 'posts.subcategory_id')
                ->join('users', 'users.id', '=', 'posts.user_id')
                ->select('posts.*', 'categories.slug AS category_name', 'subcategories.slug AS subcategory_name')
                // ->select('posts.*', 'categories.id','subcategories.id', 'users.id')
                ->allowedFilters(
                    AllowedFilter::exact('category', 'categories.slug', false),
                    AllowedFilter::exact('subcategory', 'subcategories.slug', false),
                    AllowedFilter::exact('user', 'users.id', false),
                    'title'
                )
                // ->orderByRaw("created_at DESC")
                ->orderByRaw("created_at $sort_direction")
                ->allowedIncludes(['resources', 'category', 'details', 'user', 'subcategory']);
            //Verificar si se solicito datos paginados
            if($allowPaginate){
                $postsFiltered = $queryBuilder->simplePaginate(10);
                return $this->sendPaginateResponse(200, 'success', $postsFiltered->toArray());
            }else{
                $postsFiltered = $queryBuilder->get();
                return $this->sendResponse(200, 'Publicaciones obtenidas correctamente', $postsFiltered);
            }
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }


    /**
     * Retorna el detalle de una publicación
     * @param integer $id
     *
     * @return array
     */
    public function detail($id) {
        try {
            $post = Post::findById($id)->with(['resources', 'category', 'user', 'subcategory'])->first();
            //Verificar si existe el post
            if (!is_null($post)) {
                return $this->sendResponse(200, 'success', $post);
            }
            //Si no existe mando un error
            return $this->sendError(404, 'Publicación Solicitada no existe', []);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Crea una publicación de emergencia
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function createEmergency(Request $request) {
        try {
            $token_decoded = $request->get('token');
            // dd($token_decoded);
            // Obtener los datos de la request
            $validatorEmergency = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
                "ubication" => ['required'],

                "ubication.latitude" => ['required', 'numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],

                "ubication.longitude" => ['required', 'numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],

                "ubication.description" => ['required', 'string'],

                "ubication.address" => ['required', 'string'],

                "images" => ['array'],
                "images.*" => [new Base64FormatImage],
            ], $this->ubicationErrors);
            // Verificar la Request
            if (!$validatorEmergency->fails()) {
                $emergencyData = $request->all();
                
                $ubication = $emergencyData['ubication'];
                // dd();
                $ubication['title'] =  $emergencyData['title'];
                //dd($ubication);
                // $emergencyData['ubication'] = $utils->mapUbication($emergencyData['ubication']);
                $imagesPost = ($request->filled('images')) ? $emergencyData['images'] : [];
                $category = Category::slug('emergencia')->first();
                //d($category);
                //$this->sendDebugResponse($emergencyData)
                $post = new Post();
                $post->title = $emergencyData['title'];
                $post->description = $emergencyData['description'];
                $post->user_id = $token_decoded->user->id;
                $post->category_id = $category->id;
                $post->date = date("Y-m-d");
                $post->time = date("H:i:s");
                $post->state = 1;
                $post->ubication = json_encode($ubication);
                $post->save();
                //Guardar Resources
                // if (!is_null($imagesPost) && count($imagesPost) > 0) {
                //     foreach ($imagesPost as $image_b64) {
                //         $image = new Image();
                //         $imageApi = new ApiImages();
                //         $image_name = $imageApi->savePostImageApi($image_b64);
                //         $image->url = $image_name;
                //         $image->post_id = $post->id;
                //         $image->save();
                //     }
                // }

                //Enviar notificaciones a moderadores

                //dd($post);
                $rolModerador = Role::where('slug', 'moderador')->first();
                $moderadores = $rolModerador->users()->get();
                // $usersDevicesIds = [];
                foreach($moderadores as $moderador){
                    // $devices_ids = OnesignalNotification::getUserDevices($moderador->id);
                    $moderador->notify($post);
                    // foreach($devices_ids as $device_id){
                    //     array_push($usersDevicesIds, $device_id);
                    // }
                }
                //d($usersDevicesIds);
              
                // OnesignalNotification::sendNotificationByPlayersID(
                //     $title = $post->title, 
                //     $description = substr($post->description, 25), 
                //     $aditionalData = [
                //         "post" => $post,
                //         "category" => $post->category,
                //         "subcategory" => $post->subcategory
                //     ],
                //     $specificIDs = $usersDevicesIds
                // );
                //Enviar notification a todos
                OnesignalNotification::sendNotificationBySegments(
                    $title = $post->title, 
                    $description = substr($post->description, 25), 
                    $aditionalData = [
                        "post" => $post,
                        "category" => $post->category,
                        "subcategory" => $post->subcategory
                ]);

                

                
                //die();
                
                //Respuesta Api
                return $this->sendResponse(200, "Emergency Created", [
                    'id' => $post->id
                ]);
            }
            // Si falla la validación envio un error
            return $this->sendError(400, "Error en la Petición", $validatorEmergency->messages());
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Crea una publicación de un problema social
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function createSocialProblem(Request $request) {
        try {
            // $utils = new \Utils();
            $token_decoded = $request->get('token');
            //Validar Petición
            $validatorSocialProblem = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
                "subcategory_id" => 'required|integer',
                "images" => ['array'],
                "images.*" => [new Base64FormatImage],
                'description' => 'required|string',
                "ubication" => ['required'],
                "ubication.latitude" => ['required', 'numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                "ubication.longitude" => ['required', 'numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                "ubication.description" => ['required', 'string'],
                "ubication.address" => ['required', 'string'],
            ], $this->ubicationErrors);
            // Verificar la Request
            if (!$validatorSocialProblem->fails()) {
                $socialProblemData = $request->all();
                $imagesPost = ($request->filled('images')) ? $socialProblemData['images'] : [];
                $category = Category::slug($this->categories['problemas_sociales'])->first();
                //$this->sendDebugResponse($socialProblemData);
                $post = new Post();
                $post->title = $socialProblemData['title'];
                $post->description = $socialProblemData['description'];
                $post->user_id = $token_decoded->user->id;
                $post->subcategory_id = $socialProblemData['subcategory_id'];
                $post->category_id = $category->id;
                $post->date = date("Y-m-d");
                $post->time = date("H:i:s"); 
                $post->ubication = json_encode($socialProblemData['ubication']);
                $post->save();
                // Crear Detalle Post
                // $detailPost = new Detail();
                // $detailPost->post_id = $post->id;
                // $detailPost->user_id = $token_decoded->user->id;
                // $detailPost->type = 'social_problem_report';
                // $detailPost->save();
                //Guardar Imagenes
                if (!is_null($imagesPost) && count($imagesPost) > 0) {
                    foreach ($imagesPost as $image_b64) {
                        $image = new Image();
                        $imageApi = new ApiImages();
                        $image_name = $imageApi->savePostImageApi($image_b64);
                        $image->url = $image_name;
                        $image->post_id = $post->id;
                        $image->save();
                    }
                }
                return $this->sendResponse(200, "Social Problem Created", [
                    'id' => $post->id
                ]);
            }
            // Si la validacion falla retorno un error
            return $this->sendError(400, "Error en la Petición", $validatorSocialProblem->messages());
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el servidor", ['server_error' => $e->getMessage()]);
        }
    }

}
