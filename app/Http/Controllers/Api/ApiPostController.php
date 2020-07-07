<?php

namespace App\Http\Controllers\Api;

use Exception;
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
use App\Helpers\OnesignalNotification;
use App\HelpersClass\AdditionalData as HelperAdditionalData;
use App\HelpersClass\Ubication as HelperUbication;
use App\Notifications\PublicationReport;

//Request
use App\Http\Requests\Api\ApiCreateEmergencyRequest;
use App\Http\Requests\Api\ApiCreateProblemRequest;

class ApiPostController extends ApiBaseController
{
    /**
    * Constructor Clase
    *
    * @return void
    */
    public function __construct()
    {
    }


    /**
      * Filtra las publicaciones por categoria, subcategoria o titulo y permite paginación
      * @param \Illuminate\Http\Request $request
      * @param string $slug
      *
      * @return array
      */
    public function index(Request $request)
    {
        try {
            $queryset = Post::with(['resources', 'category', 'user', 'subcategory', 'reactions']);
            //FILTROS PERMITIDOS
            $filterCategory = ($request->input('category')) ? $request->category: -1;
            $filterSubcategory = ($request->input('subcategory')) ? $request->subcategory: -1;
            $filterUser = $request->input('user') ? intval($request->user): -1;
            $filterByTitle = $request->input('title') ? $request->title : '';
            $filterByPolice = $request->input('police') ? intval($request->police): -1;
            $filterActive = $request->input('active') ? intval($request->active): -1;
            $filterStatusAttendance = $request->input('status_attendance') != null ? $request->status_attendance: '';
            $filterIsPolice = $request->input('is_police') != null ? $request->is_police: '';
            $filterSize =  $request->input('size') ? intval($request->size): 20;
            //APLICAR FILTROS
            if ($filterCategory != -1) {
                $category = Category::slug($filterCategory)->first();
                $queryset = $queryset->categoryId(($category) ? $category->id: -1);
            }
            
            if ($filterSubcategory != -1) {
                $subcategory = Subcategory::slug($filterSubcategory)->first();
                $queryset = $queryset->subCategoryId(($subcategory) ? $subcategory->id: -1);
            }
          
    
            if ($filterUser != -1) {
                $queryset = $queryset->userId($filterUser);
            }

           
            if ($filterActive != -1) {
                $queryset = $queryset->where('state', 1);
            }

            if ($filterByPolice != -1) {
                $queryset = $queryset->where('additional_data->attended->who->id', $filterByPolice);
            }

            if ($filterByTitle != '') {
                $queryset = $queryset->where('title', 'LIKE', "%$filterByTitle%");
            }
            
            if ($filterStatusAttendance != '') {
                $queryset = $queryset->where('additional_data->status_attendance', $filterStatusAttendance);
            }
            if($filterCategory == 'problema'){
                $queryset = $queryset->whereNotIn('additional_data->status_attendance', ['pendiente']);
            }
            
            if($filterCategory == 'emergencia' && $filterIsPolice == -1){
                $queryset = $queryset->whereNotIn('additional_data->status_attendance', ['pendiente']);
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
    public function detail($id)
    {
        try {
            $post = Post::findById($id)->where('state', 1)->with(['resources', 'category', 'user', 'subcategory', 'reactions'])->first();
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
    public function atenderEmergencia(Request $request)
    {
        $type_notification = "emergency_reported";
        $token_decoded = $request->get('token');
        $validatorAtenderEmergencia = Validator::make($request->all(), [
            "emergencia_id" => ['required', 'int'],
        ]);
        if ($validatorAtenderEmergencia->fails()) {
            return $this->sendError(400, "Datos no válidos", $validatorAtenderEmergencia->messages());
        }
        $police_user = User::findById($token_decoded->user->id)->first();
        if (!$police_user) {
            return $this->sendError(400, "Policia No encontrado", ['policia' => 'policia no encontrado']);
        }

      

        $postId = $request->get("emergencia_id");
        //Cambiar estado post
        $emergency = Post::findById($postId)->first();
        if (is_null($emergency)) {
            return $this->sendError(400, "Publicación No existe", ["emergencia" => "publicación no existe"]);
        }

        $report_user = User::findById($emergency->user_id)->first();
        if (!$report_user) {
            return $this->sendError(400, "Usuario No encontrado", ['usuario' => 'Usuario no encontrado']);
        }

        //Actualizar Aditional Data
        $aditionalData = new HelperAdditionalData();
        $aditionalData->setInfoEmergency([
            "status_attendance" => 'atendido',
            "attended"=>[
                'who'=> $token_decoded->user,
                'date'=> date('Y-m-d H:i:s')
            ]
        ]);
        $emergency->additional_data = array_merge($emergency->additional_data ?? [], $aditionalData->getInfoEmergency());
        $emergency->state = 0;
        $emergency->save();
        //Obtener post con todos los datos necesarios
        // $post_updated = Post::findById($emergency->id)->with(["category", "subcategory"])->first();
        $category = Category::where('slug', 'emergencia')->first();
        $post_updated = $category->posts()->where('id', $emergency->id)->with('category')->first();
        //Notificar al usuario que creo el post sobre quien lo va a atender
        $title_noti = "Tu reporte de emergencia fue aceptado";
        $description_noti = "El policia " . $token_decoded->user->fullname . " ha aceptado atender tu emergencia";
        $user_devices = OnesignalNotification::getUserDevices($report_user->id);
        if (!is_null($user_devices) && count($user_devices) > 0) {
            //Enviar notification al usuario en especifico
            OnesignalNotification::sendNotificationByPlayersID(
                $title_noti,
                $description_noti,
                ["post" => [
                    'id' => $post_updated->id,
                    'category_slug' => $post_updated->category->slug,
                ]],
                $user_devices
            );
        }
        
        $report_user->notify(
            new PostNotification(
                $post_updated,
                $title_noti,
                $description_noti,
                $type_notification,
                $report_user
            )
        );
        //Enviar notificaciones a moderadores
        $rolModerador = Role::where('slug', 'moderador')->first();
 
        $moderadores = $rolModerador->users()->get();
        $title_notification_moderador = 'Una emergencia ha sido atendida';
        $description_notification_moderador = 'El usuario ' . $token_decoded->user->fullname . ' ha aceptado atender una emergencia';
        //Notificar Moderadores
        foreach ($moderadores as $moderador) {
            $moderador->notify(
                new PostNotification(
                    $emergency,
                    $title_notification_moderador,
                    $description_notification_moderador,
                    $type_notification
                )
            );

            $user_devices_moderador= OnesignalNotification::getUserDevices($moderador->id);
            if (!is_null($user_devices_moderador) && count($user_devices_moderador) > 0) {
                //Enviar notification al usuario en especifico
                OnesignalNotification::sendNotificationByPlayersID(
                    $title_notification_moderador,
                    $description_notification_moderador,
                    ["post" => [
                        'id' => $post_updated->id,
                        'category_slug' => $post_updated->category->slug,
                    ]],
                    $user_devices_moderador
                );
            }
        }
        return $this->sendResponse(200, "Solicitud de Atención Registrada Correctamente", ["emergency" => $emergency]);
    }

    /*Guardar motivo por el cual un policia rechaza atender emergencia
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */
    public function rechazarEmergencia(Request $request)
    {
        $type_notification = "emergency_reported";
        $token_decoded = $request->get('token');
        //Validar Formulario
        $validatorRechazarEmergencia = Validator::make($request->all(), [
            "motivo" => ['required', 'string'],
            "emergencia_id" => ['required', 'int'],
        ]);
        if ($validatorRechazarEmergencia->fails()) {
            return $this->sendError(400, "Datos no válidos", $validatorRechazarEmergencia->messages());
        }

        $police_user = User::findById($token_decoded->user->id)->first();
        if (!$police_user) {
            return $this->sendError(400, "Policia No encontrado", ['policia' => 'policia no encontrado']);
        }
        //Obtener valor motivo
        try {
            //Guardar motivo rechazo
            $emergency = Post::findById($request->emergencia_id)->first();
            if (!$emergency) {
                return $this->sendError(404, "Emergencia no encontrada", ["emergency" => "La Emergencia solicitada no existe"]);
            }

            $report_user = User::findById($emergency->user_id)->first();
            if (!$report_user) {
                return $this->sendError(400, "Usuario No encontrado", ['usuario' => 'Usuario no encontrado']);
            }
            
            //Actualizar Aditional Data
            $aditionalData = new HelperAdditionalData();
            $aditionalData->setInfoEmergency([
                "status_attendance" => 'rechazado',
                "rechazed"=>[
                    'who'=> $token_decoded->user,
                    'reason'=> $request->motivo,
                    'date'=> date('Y-m-d H:i:s')
                ]
                ]);
            $emergency->additional_data = array_merge($emergency->additional_data ?? [], $aditionalData->getInfoEmergency());
            $emergency->state = 0;
            $emergency->save();
            //Obtener post con todos los datos necesarios
            $category = Category::where('slug', 'emergencia')->first();
            $post_updated = $category->posts()->where('id', $emergency->id)->with('category')->first();
            //Enviar Notificación
            $title_noti = "Tu solicitud de emergencia fue rechazada";
            $description_noti = "El policia " . $police_user->fullname . " no puede atender su reporte de emergencia por el siguiente mótivo: " .  $request->motivo;
            $user_devices = OnesignalNotification::getUserDevices($report_user->id);
            if (!is_null($user_devices) && count($user_devices) > 0) {
                //Enviar notification al usuario en especifico
                OnesignalNotification::sendNotificationByPlayersID(
                    $title_noti,
                    $description_noti,
                    ["post" => [
                        'id' => $post_updated->id,
                        'category_slug' => $post_updated->category->slug,
                    ]],
                    $user_devices
                );
            }
            $report_user->notify(
                new PostNotification(
                    $post_updated,
                    $title_noti,
                    $description_noti,
                    $type_notification
                )
            );
            //Enviar notificaciones a moderadores
            $rolModerador = Role::where('slug', 'moderador')->first();
            $moderadores = $rolModerador->users()->get();
   
            $title_notification_moderador = 'Una emergencia ha sido rechazada';
            $description_notification_moderador = 'El usuario ' . $token_decoded->user->fullname . ' ha rechazado atender una emergencia';
            //Notificar Moderadores
            foreach ($moderadores as $moderador) {
                $moderador->notify(
                    new PostNotification(
                        $post_updated,
                        $title_noti,
                        $description_noti,
                        $type_notification,
                        $report_user
                    )
                );
  
                $user_devices_moderador= OnesignalNotification::getUserDevices($moderador->id);
                if (!is_null($user_devices_moderador) && count($user_devices_moderador) > 0) {
                    //Enviar notification al usuario en especifico
                    OnesignalNotification::sendNotificationByPlayersID(
                        $title_notification_moderador,
                        $description_notification_moderador,
                        ["post" => [
                            'id' => $post_updated->id,
                            'category_slug' => $post_updated->category->slug,
                        ]],
                        $user_devices_moderador
                    );
                }
            }
            //Retornar mensaje
            return $this->sendResponse(200, "Motivo guardado correctamente", ["rechazo_emergencia" => "El Motivo del Rechazo de la Emergencia se guardo correctamente", 'emergency' => $emergency]);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Crear una publicación de emergencia
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function createEmergency(ApiCreateEmergencyRequest $request)
    {
        try {
            $type_notification = "emergency_reported";
            $validated = $request->validated();
            $token_decoded = $request->get('token');

            $category = Category::slug(Config::get('siu_config.categorias')['emergencias'])->first();
            $post = new Post();
            $post->title = $request->title;
            $post->description = $request->description;
            $post->user_id = $token_decoded->user->id;
            $post->category_id = $category->id;
            $post->ubication = $request->ubication;
           
            $additionalData = new HelperAdditionalData();
            $post->additional_data = $additionalData->getInfoEmergency();
            
            $post->state = 0;
            $post->save();
            //Guardar Resources
            if (!is_null($request->images) && count($request->images) > 0) {
                foreach ($request->images as $image_file) {
                    $resource = new Resource();
                    $imageApi = new ApiImages();
                    $image_name = $imageApi->savePostFileImageApi($image_file, null, true);
                    $resource->url = $image_name;
                    $resource->type = "image";
                    $resource->post_id = $post->id;
                    $resource->save();
                }
            }

            //Enviar notificaciones a policias
            $rolPolicia = Role::where('slug', 'policia')->first();
            $policias = $rolPolicia->users()->get();

            // $new_post = Post::findById($post->id)->with(["category", "subcategory"])->first();
            $category = Category::where('slug', 'emergencia')->first();
            $new_post = $category->posts()->where('id', $post->id)->with('category')->first();
            //Notificar Emergencia a los Policias
            $title_notification_policia = "Nueva emergencia reportada";
            $description_notification_policia = "El usuario " . $new_post->user->fullname . " ha reportado una emergencia";

            foreach ($policias as $policia) {
                $policia->notify(
                    new PostNotification(
                        $new_post,
                        $title_notification_policia,
                        $description_notification_policia,
                        $type_notification
                    )
                );

                $user_devices_policia = OnesignalNotification::getUserDevices($policia->id);
                if (!is_null($user_devices_policia) && count($user_devices_policia) > 0) {
                    //Enviar notification al usuario en especifico
                    OnesignalNotification::sendNotificationByPlayersID(
                        $title_notification_policia,
                        $description_notification_policia,
                        [
                            "post" => $new_post
                        ],
                        $user_devices_policia
                    );
                }
            }
            //Enviar notification al usuario que creo la emergencia
            $title_notification_user = "Tu emergencia fue reportada correctamente";
            $description_notification_user = "Cuando un policia atienda tu reporte, seras notificado inmediatamente";

            $new_post->user->notify(
                new PostNotification(
                    $new_post,
                    $title_notification_user,
                    $description_notification_user,
                    $type_notification
                )
            );

            $user_devices_emergencia = OnesignalNotification::getUserDevices($new_post->user->id);
            if (!is_null($user_devices_emergencia) && count($user_devices_emergencia) > 0) {
                OnesignalNotification::sendNotificationByPlayersID(
                    $title_notification_user,
                    $description_notification_user,
                    [
                            "post" => $new_post
                        ],
                    $user_devices_emergencia
                );
            }

            //Enviar notificaciones a moderadores
            $rolModerador = Role::where('slug', 'moderador')->first();
            $moderadores = $rolModerador->users()->get();
            $title_notification_moderador = "Una emergencia ha sido reportada";
            $description_notification_moderador =  'El usuario ' . $token_decoded->user->fullname . ' ha reportado una emergencia';            //Notificar Moderadores
            foreach ($moderadores as $moderador) {
                $moderador->notify(
                    new PublicationReport(
                        $type_notification, //tipo de la notificación
                        $new_post->category->name, //título de la notificación
                        $new_post->user->fullname . ' ha reportado una emergencia', //descripcción de la notificación
                        $new_post, // post que almacena la notificación
                        $post->user //morador que reportó el problema social
                    )
                );
  
                $user_devices_moderador= OnesignalNotification::getUserDevices($moderador->id);
                if (!is_null($user_devices_moderador) && count($user_devices_moderador) > 0) {
                    //Enviar notification al usuario en especifico
                    OnesignalNotification::sendNotificationByPlayersID(
                        $title_notification_moderador,
                        $description_notification_moderador,
                        ["post" => [
                            'id' => $new_post->id,
                            'category_slug' => $new_post->category->slug,
                        ]],
                        $user_devices_moderador
                    );
                }
            }
            //Respuesta Api
            return $this->sendResponse(200, "Emergency Created", $new_post);
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
    public function createSocialProblem(ApiCreateProblemRequest $request)
    {
        try {
            //Validar Petición
            $validated = $request->validated();
            $category = Category::slug(Config::get('siu_config.categorias')['problemas_sociales'])->first();
            $type_notification = "problem_reported";
            
            $post = new Post();
            $post->title = $request->title;
            $post->description = $request->description;
            $post->user_id = $request->token->user->id;
            $post->subcategory_id = $request->subcategory_id;
            $post->category_id = $category->id;
            $post->state = 0;
            $post->ubication = $request->ubication;
            $additionalData = new HelperAdditionalData();
            $post->additional_data = $additionalData->getInfoEmergency();
            $post->save();
            //Guardar Recursos
            if (!is_null($request->images) && count($request->images) > 0) {
                foreach ($request->images as $image_file) {
                    $resource = new Resource();
                    $imageApi = new ApiImages();
                    $image_name = $imageApi->savePostFileImageApi($image_file, null, true);
                    $resource->url = $image_name;
                    $resource->type = "image";
                    $resource->post_id = $post->id;
                    $resource->save();
                }
            }
            //Enviar notificaciones a moderadores
            $rolModerador = Role::where('slug', 'moderador')->first();
 
            $moderadores = $rolModerador->users()->get();
 
            $category = Category::where('slug', 'problema')->first();
            $new_post = $category->posts()->where('id', $post->id)->with('category')->first();
            $title_notification_moderador = 'Un nuevo problema social ha sido reportado';
            $description_notification_moderador = 'El usuario ' . $new_post->user->fullname . ' ha reportado un problema social';
            //Notificar Moderadores
            foreach ($moderadores as $moderador) {
                $moderador->notify(
                    // new PostNotification(
                    //     $new_post,
                    //     $title_notification_moderador,
                    //     $description_notification_moderador,
                    //     $type_notification
                    // )
                    new PublicationReport(
                        $type_notification, //tipo de la notificación
                        $new_post->subcategory->name, //título de la notificación
                        $new_post->user->fullname . ' ha reportado un problema social', //descripcción de la notificación
                        $new_post, // post que almacena la notificación
                        $post->user //morador que reportó el problema social
                    )
                );

                $user_devices_moderador= OnesignalNotification::getUserDevices($moderador->id);
                if (!is_null($user_devices_moderador) && count($user_devices_moderador) > 0) {
                    //Enviar notification al usuario en especifico
                    OnesignalNotification::sendNotificationByPlayersID(
                        $title_notification_moderador,
                        $description_notification_moderador,
                        ["post" => [
                            'id' => $new_post->id,
                            'category_slug' => $new_post->category->slug,
                        ]],
                        $user_devices_moderador
                    );
                }
            }
            //Enviar notification al usuario que creo el problema social
            $title_notification_user = "Tu problema social fue reportado correctamente";
            $description_notification_user = "Cuando tu publicación sea aprobada, seras notificado inmediatamente";

            $new_post->user->notify(
                new PostNotification(
                    $new_post,
                    $title_notification_user,
                    $description_notification_user,
                    $type_notification
                )
            );

            $user_devices_problema_social = OnesignalNotification::getUserDevices($new_post->user->id);
            if (!is_null($user_devices_problema_social) && count($user_devices_problema_social) > 0) {
                OnesignalNotification::sendNotificationByPlayersID(
                    $title_notification_user,
                    $description_notification_user,
                    [
                            "post" => $post
                        ],
                    $user_devices_problema_social
                );
            }
            return $this->sendResponse(200, "Social Problem Created", $new_post);
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el servidor", ['server_error' => $e->getMessage()]);
        }
    }
}
