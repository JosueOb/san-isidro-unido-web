<?php

namespace App\Http\Controllers\Api;

use Caffeinated\Shinobi\Models\Role;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Category;
use App\Reaction;
use App\Helpers\ApiImages;
use App\Http\Controllers\Api\ApiBaseController;
use App\Resource;
use App\Post;
use App\User;
use App\Subcategory;
use App\Notifications\PostNotification;
use App\Rules\Api\Base64FormatImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Helpers\OnesignalNotification;
use App\HelpersClass\AdditionalData as AdditionalDataCls;
use App\HelpersClass\Ubication as UbicationCls;

// use AdditionalData;

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
        $this->ubicationValidationRules = [
           "latitude" => ['required', 'numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],

           "longitude" => ['required', 'numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],

           "description" => ['required', 'string'],

           "address" => ['required', 'string'],
       ];
       $this->baseAditionalData = [
            "log_emergency" => [
                "attended_by" => null,
                'rechazed_by' => []
            ],
            "log_event" => [
                "responsable" => null
            ],
            "log_social_problem" => [
                
            ],
            "log_neighborhood_activity" => [
                
            ],
            "log_post" => [
                "approved_by" => null
            ]
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
        try {

            $queryset = Post::with(['resources', 'category', 'user', 'subcategory', 'reactions']);
            //FILTROS PERMITIDOS
            $filterCategory = ($request->get('category')) ? $request->get('category'): -1;
            $filterSubcategory = ($request->get('subcategory')) ? $request->get('subcategory'): -1;
            $filterUser = ($request->get('user')) ? intval($request->get('user')): -1;
            $filterByTitle = ($request->get('title')) ? $request->get('title'): '';
            $filterByPolice = ($request->get('police')) ? intval($request->get('police')): -1;
            $filterIsAttended = ($request->get('is_attended') != null) ? intval($request->get('is_attended')): -1;
            $filterSize =  ($request->get('size')) ? intval($request->get('size')): 20;
            //APLICAR FILTROS
            if($filterCategory != -1){
                $category = Category::slug($filterCategory)->first();
                $queryset = $queryset->categoryId(($category) ? $category->id: -1);
            }
            
            if($filterSubcategory != -1){
                $subcategory = Subcategory::slug($filterSubcategory)->first();
                $queryset = $queryset->subCategoryId(($subcategory) ? $subcategory->id: -1);
            }
    
            if($filterUser != -1){
                $queryset = $queryset->userId($filterUser);
            }

            if($filterByPolice != -1){
                $queryset = $queryset->where('additional_data->info_emergency->attended_by->id', $filterByPolice);
            }

            if($filterIsAttended != -1){
                $queryset = $queryset->where('is_attended', $filterIsAttended);
            }

            if($filterByTitle != ''){
                $queryset = $queryset->where('title', 'LIKE', "%$filterByTitle%");
            }
            //Retornar Paginacion y datos ordenados descendentemente para devolver los mas nuevos primero
            $posts = $queryset->orderBy('created_at', 'DESC')->simplePaginate($filterSize)->toArray();
            return $this->sendPaginateResponse(200, 'Datos Obtenidos', $posts);         
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
            $post = Post::findById($id)->with(['resources', 'category', 'user', 'subcategory', 'reactions'])->first();
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

    /*
    * Aceptar atender una emergencia(Policia)
    
    */
    public function atenderEmergencia(Request $request){
        $token_decoded = $request->get('token');
        $validatorAtenderEmergencia = Validator::make($request->all(), [
            "emergencia_id" => ['required', 'int'],
        ]);
        if ($validatorAtenderEmergencia->fails()) {
            return $this->sendError(400, "Datos no válidos", $validatorAtenderEmergencia->messages());
        }
        $postId = $request->get("emergencia_id");
        //Cambiar estado post
        $emergencia = Post::findById($postId)->first();
        if (is_null($emergencia)) {
            return $this->sendError(400, "Publicación No existe", ["emergencia" => "publicación no existe"]);
        }
        $post_info_log = json_decode($emergencia->additional_data, true);

        $post_info_log["log_emergency"]["policia"] = $token_decoded->user;
        $post_info_log["log_event"] = 
        (array_key_exists("log_event",$post_info_log) && $post_info_log["log_event"]) ? $post_info_log["log_event"]: null;
        $post_info_log["log_post"] = (array_key_exists("log_post",$post_info_log) && $post_info_log["log_post"]) ? $post_info_log["log_post"]: null;
        $emergencia->is_attended = 1;
        $emergencia->additional_data = $post_info_log;
        
        $emergencia->save();
        //Notificar al usuario que creo el post sobre quien lo va a atender
        $user = User::findById($emergencia->user_id)->first();
        if(!is_null($user)){
            $user_devices = OnesignalNotification::getUserDevices($emergencia->user_id);
            if(!is_null($user_devices) && count($user_devices) > 0){
                //Enviar notification al usuario en especifico
               OnesignalNotification::sendNotificationByPlayersID(
                   $title_noti = "Tu solicitud de emergencia fue aceptada", 
                   $description_noti = "El policia " . $user->first_name . " ha aceptado atender tu emergencia", 
                   [
                       "title" => $title_noti,
                       "message" => $description_noti,
                       "post" => $emergencia
                   ],
                   $user_devices
               );
            }
            return $this->sendResponse(200, "Solicitud de Atención Registrada Correctamente", ["emergency" => $emergencia]);
        }
        return $this->sendError(400, "Usuario No existe", ["usuario" => "usuario no existe"]);
    }

    /*Guardar motivo por el cual un policia rechaza atender emergencia
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */
    public function rechazarEmergencia(Request $request){
        $token_decoded = $request->get('token');
        //Validar Formulario
        $validatorRechazarEmergencia = Validator::make($request->all(), [
            "motivo" => ['required', 'string'],
            "emergencia_id" => ['required', 'int'],
        ]);
        if ($validatorRechazarEmergencia->fails()) {
            return $this->sendError(400, "Datos no válidos", $validatorRechazarEmergencia->messages());
        }
        //Obtener valor motivo
        try{
            //Guardar motivo rechazo
            $emergency = Post::findById($request->emergencia_id)->first();
            if(!$emergency){
                return $this->sendError(404, "Emergencia no encontrada", ["emergency" => "La Emergencia solicitada no existe"]);
            }
            $emergency->is_attended = 0;           
            $oldAditionalData = ($emergency->additional_data != null) ? $emergency->additional_data: [];
            $arrayUpdatedAditionalData = 
            [
                "info_emergency" => [
                    "attended_by" => null,
                    "rechazed_by" => $token_decoded->user,
                    "rechazed_reason" => $request->motivo
                ],
                "info_event" => [
                    "responsable" => null
                ],
                "info_social_problem" => null,
                "info_activity" => null,
                "info_post" => [
                    "approved_by" => null
                    ]
            ];
            $result = array_merge($oldAditionalData, $arrayUpdatedAditionalData);
            $emergency->additional_data = $result;
            $emergency->save();
            //Retornar mensaje
            return $this->sendResponse(200, "Motivo guardado correctamente", ["rechazo_emergencia" => "El Motivo del Rechazo de la Emergencia se guardo correctamente", 'emergency' => $emergency]);
            // return $this->sendError(500, "Falta Implementación", ["Form" => "Pendiente de guardado"]);
        }catch (Exception $e) {
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
            // Obtener los datos de la request
            $validatorEmergency = Validator::make($request->all(), [
                'title' => 'required|string|max:150',
                'description' => 'required|string',
                "ubication" => ['required', 'array'],
                "images" => ['array'],
            ], $this->ubicationErrors);
           
            $ubication = $request->ubication;
          
            $validatorUbication = Validator::make($ubication, $this->ubicationValidationRules);           
          
            // Verificar la Request
            if (!$validatorEmergency->fails()) {
                //Verificar Ubicacion
                if ($validatorUbication->fails()){
                    return $this->sendError(400, "Error en la Petición", $validatorUbication->messages());
                }
                // $ubication = $ubicationDecode;
                $imagesPost = $request->images;
                $category = Category::slug($this->categories['emergencias'])->first();
                // $imageApi = new ApiImages();
                // $image_name = $imageApi->savePostFileImageApi($imagesPost[0]);
                $post = new Post();
                $post->title = $request->title;
                $post->description = $request->description;
                $post->user_id = $token_decoded->user->id;
                $post->category_id = $category->id;
                $post->date = date("Y-m-d");
                $post->time = date("H:i:s");
                $post->state = 1;
                $post->ubication = $ubication;
                // $post->police_id = null;
                // $post->police_id = null;
                $aditionalData = new AdditionalDataCls();
                $aditionalDataSave = (isset($post->additional_data)) ? $post->additional_data: $aditionalData->getAll();
                $post->additional_data = $aditionalDataSave;
                $post->save();
                //Guardar Resources
                if (!is_null($imagesPost) && count($imagesPost) > 0) {
                    foreach ($imagesPost as $image_file) {
                        $resource = new Resource();
                        $imageApi = new ApiImages();
                        $image_name = $imageApi->savePostFileImageApi($image_file);
                        $resource->url = $image_name;
                        $resource->type = "image";
                        $resource->post_id = $post->id;
                        $resource->save();
                    }
                }

                //Enviar notificaciones a moderadores
                $rolPolicia = Role::where('slug', 'policia')->first();
                $policias = $rolPolicia->users()->get();

                $new_post = Post::findById($post->id)->with(["category", "subcategory", 'resources', 'reactions'])->first();
                //Notificar Emergencia a los Policias
                $title_notification_policia = "Nueva emergencia reportada";
                $description_notification_policia = "El usuario " . $new_post->user->first_name . " ha reportado una emergencia";

                foreach($policias as $policia){
                    $policia->notify(new PostNotification($new_post, $title_notification_policia, $description_notification_policia));

                    $user_devices_policia = OnesignalNotification::getUserDevices($policia->id);
                    if(!is_null($user_devices_policia) && count($user_devices_policia) > 0){
                        //Enviar notification al usuario en especifico
                        OnesignalNotification::sendNotificationByPlayersID(
                            [
                                "title" => $title_notification_policia,
                                "message" => $description_notification_policia,
                                "post" => $new_post
                            ],
                            $user_devices_policia
                        );
                    }
                }
                //Enviar notification al usuario que creo la emergencia
                $title_notification_user = "Tu emergencia fue reportada correctamente";
                $description_notification_user = "Cuando un policia atienda tu reporte, seras notificado inmediatamente";

                $new_post->user->notify(new PostNotification($new_post, $title_notification_user, $description_notification_user));

                $user_devices_emergencia = OnesignalNotification::getUserDevices($new_post->user->id);
                if(!is_null($user_devices_emergencia) && count($user_devices_emergencia) > 0){
                    OnesignalNotification::sendNotificationByPlayersID(
                        $title_notification_user, 
                        $description_notification_user, 
                        [
                            "title" => $title_notification_user,
                            "message" => $description_notification_user,
                            "post" => $new_post
                        ],
                        $user_devices_emergencia
                    );
                }
                //Respuesta Api
                return $this->sendResponse(200, "Emergency Created", $new_post);
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
                'title' => 'required|string|max:150',
                'description' => 'required|string',
                "ubication" => ['required', 'array'],
                "images" => ['array'],
                "subcategory_id" => 'required|integer'
            ], $this->ubicationErrors);
            $ubication = $request->ubication;
            //Validacion Ubicacion
            $validatorUbication = Validator::make($ubication, $this->ubicationValidationRules);
            // Verificar la Request
            if (!$validatorSocialProblem->fails()) {
                //Verificar Ubicacion
                if ($validatorUbication->fails()){
                    return $this->sendError(400, "Error en la Petición", $validatorUbication->messages());
                }
                // $ubication = $ubicationDecode;
                $imagesPost = $request->images;
                $category = Category::slug($this->categories['problemas_sociales'])->first();
                
                $post = new Post();
                $post->title = $request->title;
                $post->description = $request->description;
                $post->user_id = $token_decoded->user->id;
                $post->subcategory_id = $request->subcategory_id;
                $post->category_id = $category->id;
                $post->date = date("Y-m-d");
                $post->time = date("H:i:s"); 
                $post->state = 1;
                $post->ubication = $ubication;
                $post->save();
                //Guardar Recursos
                if (!is_null($imagesPost) && count($imagesPost) > 0) {
                    foreach ($imagesPost as $image_file) {
                        $resource = new Resource();
                        $imageApi = new ApiImages();
                        $image_name = $imageApi->savePostFileImageApi($image_file);
                        $resource->url = $image_name;
                        $resource->type = "image";
                        $resource->post_id = $post->id;
                        $resource->save();
                    }
                }
                 //Enviar notificaciones a moderadores
                 $rolModerador = Role::where('slug', 'moderador')->first();
                 $rolPolicia = Role::where('slug', 'policia')->first();
 
                 $moderadores = $rolModerador->users()->get();
                 $policias = $rolPolicia->users()->get();
 
                 $new_post = Post::findById($post->id)->with(["category", "subcategory", 'resources', 'reactions'])->first();
                 $title_notification_moderador = 'Un nuevo problema social ha sido reportado';
                 $description_notification_moderador = 'El usuario ' . $new_post->user->first_name . ' ha reportado un problema social';
                 //Notificar Moderadores
                 foreach($moderadores as $moderador){
                     $moderador->notify(new PostNotification($new_post, $title_notification_moderador, $description_notification_moderador));

                     $user_devices_moderador= OnesignalNotification::getUserDevices($moderador->id);
                    if(!is_null($user_devices_moderador) && count($user_devices_moderador) > 0){
                        //Enviar notification al usuario en especifico
                        OnesignalNotification::sendNotificationByPlayersID(
                            [
                                "title" => $title_notification_moderador,
                                "message" => $description_notification_moderador,
                                "post" => $post
                            ],
                            $user_devices_moderador
                        );
                    }
                 }
                  //Enviar notification al usuario que creo el problema social
                $title_notification_user = "Tu problema social fue reportado correctamente";
                $description_notification_user = "Cuando tu publicación sea aprobada, seras notificado inmediatamente";

                $post->user->notify(new PostNotification($post, $title_notification_user, $description_notification_user));

                $user_devices_problema_social = OnesignalNotification::getUserDevices($new_post->user->id);
                if(!is_null($user_devices_problema_social) && count($user_devices_problema_social) > 0){
                    OnesignalNotification::sendNotificationByPlayersID(
                        $title_notification_user, 
                        $description_notification_user, 
                        [
                            "title" => $title_notification_user,
                            "message" => $description_notification_user,
                            "post" => $post
                        ],
                        $user_devices_problema_social
                    );
                }
                return $this->sendResponse(200, "Social Problem Created", $post);
            }
            // Si la validacion falla retorno un error
            return $this->sendError(400, "Error en la Petición", $validatorSocialProblem->messages());
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el servidor", ['server_error' => $e->getMessage()]);
        }
    }
}