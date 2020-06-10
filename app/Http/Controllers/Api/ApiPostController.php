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
use App\HelpersClass\AdditionalData as AdditionalDataCls;
use App\HelpersClass\Ubication as UbicationCls;

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
            $filterCategory = ($request->get('category')) ? $request->get('category'): -1;
            $filterSubcategory = ($request->get('subcategory')) ? $request->get('subcategory'): -1;
            $filterUser = ($request->get('user')) ? intval($request->get('user')): -1;
            $filterByTitle = ($request->get('title')) ? $request->get('title'): '';
            $filterByPolice = ($request->get('police')) ? intval($request->get('police')): -1;
            $filterActive = ($request->get('active')) ? intval($request->get('active')): -1;
            $filterStatusAttendance = ($request->get('status_attendance') != null) ? $request->get('status_attendance'): '';
            $filterSize =  ($request->get('size')) ? intval($request->get('size')): 20;
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
            // dd($filterStatusAttendance);
            if ($filterStatusAttendance != '') {
                $queryset = $queryset->where('additional_data->status_attendance', $filterStatusAttendance);
            }
            // dd($queryset->toSql());
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
    public function atenderEmergencia(Request $request)
    {
        $token_decoded = $request->get('token');
        $validatorAtenderEmergencia = Validator::make($request->all(), [
            "emergencia_id" => ['required', 'int'],
        ]);
        if ($validatorAtenderEmergencia->fails()) {
            return $this->sendError(400, "Datos no válidos", $validatorAtenderEmergencia->messages());
        }
        $user = User::findById($token_decoded->user->id)->first();
        if (!$user) {
            return $this->sendError(400, "Usuario No encontrado", ['usuario' => 'Usuario no encontrado']);
        }
        $postId = $request->get("emergencia_id");
        //Cambiar estado post
        $emergency = Post::findById($postId)->first();
        if (is_null($emergency)) {
            return $this->sendError(400, "Publicación No existe", ["emergencia" => "publicación no existe"]);
        }

        //Actualizar Aditional Data
        $aditionalData = new AdditionalDataCls();
        $aditionalData->setInfoEmergency([
            "status_attendance" => 'atendido',
            "attended"=>[
                'who'=> $token_decoded->user,
                'date'=> date('Y-m-d H:i:s')
            ]
        ]);
        $emergency->additional_data = array_merge($emergency->additional_data ?? [], $aditionalData->getEmergencyData());
        $emergency->state = 0;
        $emergency->save();
        //Notificar al usuario que creo el post sobre quien lo va a atender
        $title_noti = "Tu solicitud de emergencia fue aceptada";
        $description_noti = "El policia " . $token_decoded->user->fullname . " ha aceptado atender tu emergencia";
        $user_devices = OnesignalNotification::getUserDevices($emergency->user_id);
        if (!is_null($user_devices) && count($user_devices) > 0) {
            //Enviar notification al usuario en especifico
            OnesignalNotification::sendNotificationByPlayersID(
                $title_noti,
                $description_noti,
                ["post" => $emergency],
                $user_devices
            );
        }
        
        $user->notify(new PostNotification($emergency, $title_noti, $description_noti));
        return $this->sendResponse(200, "Solicitud de Atención Registrada Correctamente", ["emergency" => $emergency]);
        return $this->sendError(400, "Usuario No existe", ["usuario" => "usuario no existe"]);
    }

    /*Guardar motivo por el cual un policia rechaza atender emergencia
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */
    public function rechazarEmergencia(Request $request)
    {
        $token_decoded = $request->get('token');
        //Validar Formulario
        $validatorRechazarEmergencia = Validator::make($request->all(), [
            "motivo" => ['required', 'string'],
            "emergencia_id" => ['required', 'int'],
        ]);
        if ($validatorRechazarEmergencia->fails()) {
            return $this->sendError(400, "Datos no válidos", $validatorRechazarEmergencia->messages());
        }
        $user = User::findById($token_decoded->user->id)->first();
        if (!$user) {
            return $this->sendError(400, "Usuario No encontrado", ['usuario' => 'Usuario no encontrado']);
        }
        //Obtener valor motivo
        try {
            //Guardar motivo rechazo
            $emergency = Post::findById($request->emergencia_id)->first();
            if (!$emergency) {
                return $this->sendError(404, "Emergencia no encontrada", ["emergency" => "La Emergencia solicitada no existe"]);
            }
            
            //Actualizar Aditional Data
            $aditionalData = new AdditionalDataCls();
            $aditionalData->setInfoEmergency([
                "status_attendance" => 'rechazado',
                "rechazed"=>[
                    'who'=> $token_decoded->user,
                    'reason'=> $request->motivo,
                    'date'=> date('Y-m-d H:i:s')
                ]
                ]);
            $emergency->additional_data = array_merge($emergency->additional_data ?? [], $aditionalData->getEmergencyData());
            $emergency->state = 0;
            $emergency->save();
            //Enviar Notificación
            $title_noti = "Tu solicitud de emergencia fue rechazada";
            $description_noti = "El policia " . $user->fullname . " no puede atenderle por el mótivo: " .  $request->motivo;
            $user_devices = OnesignalNotification::getUserDevices($emergency->user_id);
            if (!is_null($user_devices) && count($user_devices) > 0) {
                //Enviar notification al usuario en especifico
                OnesignalNotification::sendNotificationByPlayersID(
                    $title_noti,
                    $description_noti,
                    ["post" => $emergency],
                    $user_devices
                );
            }
            $user->notify(new PostNotification($emergency, $title_noti, $description_noti));
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
            $validated = $request->validated();
            $token_decoded = $request->get('token');

            $category = Category::slug(Config::get('siu_config.categorias')['emergencias'])->first();
            $post = new Post();
            $post->title = $request->title;
            $post->description = $request->description;
            $post->user_id = $token_decoded->user->id;
            $post->category_id = $category->id;
            $post->ubication = $request->ubication;
            $aditionalData = new AdditionalDataCls();
            $aditionalDataSave = (isset($post->additional_data)) ? $post->additional_data: $aditionalData->getEmergencyData();
            $post->additional_data = $aditionalDataSave;
            $post->state = 0;
            $post->save();
            //Guardar Resources
            if (!is_null($request->images) && count($request->images) > 0) {
                foreach ($request->images as $image_file) {
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
            $description_notification_policia = "El usuario " . $new_post->user->fullname . " ha reportado una emergencia";

            foreach ($policias as $policia) {
                $policia->notify(new PostNotification($new_post, $title_notification_policia, $description_notification_policia));

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

            $new_post->user->notify(new PostNotification($new_post, $title_notification_user, $description_notification_user));

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
                
            $post = new Post();
            $post->title = $request->title;
            $post->description = $request->description;
            $post->user_id = $request->token->user->id;
            $post->subcategory_id = $request->subcategory_id;
            $post->category_id = $category->id;
            $post->state = 0;
            $post->ubication = $request->ubication;
            $post->save();
            //Guardar Recursos
            if (!is_null($request->images) && count($request->images) > 0) {
                foreach ($request->images as $image_file) {
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
            $description_notification_moderador = 'El usuario ' . $new_post->user->fullname . ' ha reportado un problema social';
            //Notificar Moderadores
            foreach ($moderadores as $moderador) {
                $moderador->notify(new PostNotification($new_post, $title_notification_moderador, $description_notification_moderador));

                $user_devices_moderador= OnesignalNotification::getUserDevices($moderador->id);
                if (!is_null($user_devices_moderador) && count($user_devices_moderador) > 0) {
                    //Enviar notification al usuario en especifico
                    OnesignalNotification::sendNotificationByPlayersID(
                        $title_notification_moderador,
                        $description_notification_moderador,
                        ["post" => $post],
                        $user_devices_moderador
                    );
                }
            }
            //Enviar notification al usuario que creo el problema social
            $title_notification_user = "Tu problema social fue reportado correctamente";
            $description_notification_user = "Cuando tu publicación sea aprobada, seras notificado inmediatamente";

            $post->user->notify(new PostNotification($post, $title_notification_user, $description_notification_user));

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
            return $this->sendResponse(200, "Social Problem Created", $post);
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el servidor", ['server_error' => $e->getMessage()]);
        }
    }
}
