<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Helpers\ApiImages;
use App\Helpers\JwtAuth;
use App\Helpers\Utils;
use App\Http\Controllers\Api\ApiBaseController;
use App\Post;
use App\Role;
use Illuminate\Support\Facades\Notification;
use App\Rules\Api\Base64FormatImage;
use App\Rules\Api\ProviderData;
use App\Rules\Api\ValidarCedula;
use App\SocialProfile;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiDeviceController;
use App\Helpers\OnesignalNotification;
use App\Http\Requests\Api\ApiUserProviderRequest;
use App\Http\Requests\Api\ApiEditUserProfile;
use App\Membership;
use App\HelpersClass\ResponsibleMembership;
use App\Notifications\MembershipRequest as MembershipRequestNotification;
use App\Notifications\UserVerifyEmail;

class ApiUserController extends ApiBaseController
{
    // Variables Clase
    public $validacionesFormLogin;
    public $validacionesFormRegistro;
    public $validacionesDevice;
    public $rolInvitado;
    // Constructor de la Clase

    public function __construct()
    {
        $this->validacionesFormRegistro = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'device' => 'nullable',
            'email' => 'required|email|max:255',
            'device.description' => 'string|sometimes|required',
            'device.phone_id' => 'string|sometimes|required|max:100',
            'device.phone_model' => 'string|sometimes|required|max:100'
        ];
        $this->validacionesFormLogin = [
            'email' => 'email|required|max:255',
            'device' => 'nullable',
            'device.description' => 'string|sometimes|required',
            'device.phone_id' => 'string|sometimes|required|max:100',
            'device.phone_model' => 'string|sometimes|required|max:100'
        ];
    }

    /**
    * Retorna el listado de Usuarios
    *
    * @return array
    */

    public function index(Request $request)
    {
        try {
            $filter = $request->get('filter');
            if ($filter) {
                $users = User::rolActive()->orderBy('id', 'desc')->get();
            } else {
                $users = User::orderBy('id', 'desc')->get();
            }

            return $this->sendResponse(200, 'success', $users);
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }


    /**
     * Retorna el detalle de un Usuario
     * @param integer $id
     *
     * @return array
     */
    public function detail(Request $request, $id)
    {
        $getRoles= ($request->get('roles')) ? intval($request->get('roles')): -1;
        try {
            if ($getRoles != -1) {
                $user = User::findById($id)->with(['roles', 'memberships'])->first();
            } else {
                $user = User::findById($id)->with(['memberships'])->first();
            }
           
            //Validar si el usuario existe
            if (!is_null($user)) {
                ;
                return $this->sendResponse(200, 'success', $user);
            }
            //Si no existe envio error
            return $this->sendError(404, 'usuario no existe', ['user' => 'usuario no existe']);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Registra a un usuario y retorna el token del usuario
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */

    public function register(ApiUserProviderRequest $request)
    {
        $utils = new Utils();
        try {
            $validated = $request->validated();
            // Verificar si usuario a loguearse existe
            if ($request->user_exists) {
                return $this->sendError(400, 'Usuario ya existe', ['user' => 'Usuario existente, por favor inicie sesión']);
            }
            // Agregar validacion dependiendo del proveedor de datos
            $this->validacionesFormLogin[$request->providerKey] = 'required|string';
            $registervalidator = Validator::make($request->all(), $this->validacionesFormRegistro);
            //Verificar si fallo la validacion de los datos necesarios para guardar el usuario
            if (!$registervalidator->fails()) {
                //Validar dispositivo
                $device = $request->get('device', null);
                $avatar = ($request->avatar) ? $request->avatar: 'https://ui-avatars.com/api/?name=' .
                mb_substr($request->first_name, 0, 1) . '+' . mb_substr($request->last_name, 0, 1) .
                '&size=250';
                //Crear Usuario
                $user = $this->registerNormalUser(
                    $request->first_name ?? '',
                    $request->last_name ?? '',
                    $request->email ?? '',
                    $request->provider ?? '',
                    $request->password ?? '',
                    $avatar,
                    $device
                );
                //
                if ($request->provider != 'formulario') {
                    // Verificar si debe guardar el perfil social
                    $socialProfileExists = $utils->socialProfileExists($request->provider, $request->email);
                    //Agregar perfil Social
                    if (!$socialProfileExists) {
                        $this->createUserSocial($request->social_id, $user->id, $request->provider);
                    }
                }
                $jwtAuth = new JwtAuth();
                $token = $jwtAuth->getToken($request->email, null);
                return $this->sendResponse(200, 'Usuario Registrado Correctamente', $token);
            }
            //Enviar error datos enviados
            $firstError = $registervalidator->errors()->first();
            return $this->sendError(400, $firstError, $registervalidator->messages());
        } catch (Exception $e) {
            return $this->sendError(500, 'Error en el Servidor', ['server_error' => $e->getMessage()]);
        }
    }

    private function registerNormalUser($first_name, $last_name, $email, $provider, $password, $avatar, $device = null)
    {
        $userExists = User::email($email)->first();
        if ($userExists) {
            return $userExists;
        }
        $rolInvitado = Role::slug(Config::get('siu_config.roles')['invitado'])->first();
        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = ($provider === 'formulario' && $password) ? password_hash($password, PASSWORD_DEFAULT) : null;
        $user->avatar = $avatar;
        $user->number_phone = '';
        $user->save();
        $user->roles()->attach($rolInvitado->id, [
                'state' => 1,
            ]);
        $user->notify(new UserVerifyEmail());
        if ($device) {
            $apiDeviceController = new ApiDeviceController;
            $apiDeviceController->saveDevice(
                (array_key_exists('phone_id', $device)) ? $device['phone_id']: '',
                (array_key_exists('phone_model', $device)) ? $device['phone_model']: '',
                (array_key_exists('phone_platform', $device)) ? $device['phone_platform']: 'Modelo Generico',
                (array_key_exists('description', $device)) ? $device['description']: '',
                $user->id
            );
        }
        return $user;
    }

    /*
    Función para crear un perfil social a un usuario
    */
    public function createUserSocial($socialID, $user_id, $provider)
    {
        $socialProfile = new SocialProfile();
        $socialProfile->social_id = $socialID;
        $socialProfile->user_id = $user_id;
        $socialProfile->provider = $provider;
        $socialProfile->save();
    }

    /**
    * Loguea a un usuario en la API y retorna el token del usuario
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */

    public function login(ApiUserProviderRequest $request)
    {
        try {
            $validated = $request->validated();

            // Verificar si usuario a loguearse existe
            if (!$request->user_exists && $request->providerKey != 'password') {
                return $this->register($request);
            }
            //Validar Body Login
            $this->validacionesFormLogin[$request->providerKey] = 'required|string';
            $loginvalidator = Validator::make($request->all(), $this->validacionesFormLogin);
            //Manejar login cuando paso validacion
            if (!$loginvalidator->fails()) {
                $jwtAuth = new JwtAuth();
                $device = $request->get('device', null);
                $returnDataOrToken = ($request->has('getToken')) ? true : null;
                $userExist = User::where('email', $request->email)->first();
                //Verificar si credenciales son validas
                if ($jwtAuth->singIn($request->email, $request->providerValue, $request->provider)) {
                    //guardar device si existe
                    $user = User::email($request->email)->first();
                    if ($device) {
                        $deviceExists = $user->devices()->findByPhoneId($device['phone_id'])->first();
                        if (!$deviceExists) {
                            $apiDeviceController = new ApiDeviceController;
                            $apiDeviceController->saveDevice(
                                (array_key_exists('phone_id', $device)) ? $device['phone_id']: '',
                                (array_key_exists('phone_model', $device)) ? $device['phone_model']: '',
                                (array_key_exists('phone_platform', $device)) ? $device['phone_platform']: 'Modelo Generico',
                                (array_key_exists('description', $device)) ? $device['description']: '',
                                $user->id
                            );
                        }
                    }
                    //Retornar Token
                    $token = $jwtAuth->getToken($request->email, $returnDataOrToken);
                    return $this->sendResponse(200, 'Login Correcto', $token);
                } else {
                    //Si falla login retorno error
                    switch (strtolower($request->provider)) {
                                            case 'facebook':
                                            return $this->sendError(400, 'No has asociado tu cuenta de facebook, registrate por favor', ['user' => 'No has asociado tu cuenta de facebook, registrate por favor']);
                                            break;
                                            case 'google':
                                            return $this->sendError(400, 'No has asociado tu cuenta de google, registrate por favor', ['user' => 'No has asociado tu cuenta de google, registrate por favor']);
                                            break;
                                            default:
                                            return $this->sendError(400, 'Usuario o Contraseña Inválida', ['user' => 'Usuario o Contraseña Inválida']);
                                            break;
                                        }
                }
            }
            //Si validador falla retorno error
            return $this->sendError(400, $loginvalidator->errors()->first(), $loginvalidator->messages());
        } catch (Exception $e) {
            return $this->sendError(500, 'Error en el Servidor', ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Verifica la validez de un token
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */

    public function checkToken(Request $request)
    {
        try {
            $jwtAuth = new JwtAuth();
            $token = $request->header('Authorization');
            $validToken = $jwtAuth->checkToken($token);
            //Validar Token
            $tokenResponse = [];
            if ($validToken) {
                $tokenResponse['token'] = 'valid';
            } else {
                $tokenResponse['token'] = 'invalid';
            }
            //Retornar token valido o invalido
            return $this->sendResponse(200, 'error', $tokenResponse);
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Crea una solicitud de afiliación
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */

    public function requestAfiliation(Request $request)
    {
        $utils = new Utils();
        $jwtAuth = new JwtAuth();
        $token_decoded = $request->token;
        try {
            $validatorAfiliation = Validator::make($request->all(), [
                                    'basic_service_image' => ['required', 'mimes:png,jpeg,jpg'],
                                    'cedula' => ['required', 'string', new ValidarCedula()],
                                ]);
            // Verificar si el validador falla
            if (!$validatorAfiliation->fails()) {
                $user = User::findById($token_decoded->user->id)->first();
                //Validar si existe el usuario
                if (!is_null($user)) {
                    $imageApi = new ApiImages();
                    $image_name = $imageApi->saveAfiliationImageApi($request->basic_service_image, null, $user->fullname.'_afiliation', false);
                    //Guardar solicitud de afiliación
                    $membership = new Membership();
                    $membership->identity_card = $request->cedula;
                    $membership->basic_service_image = $image_name;
                    $membership->status_attendance = 'pendiente';
                    $membership->responsible = '';
                    $membership->user_id = $user->id;
                    $membership->save();
                    
                    $afiliation_title_noti = 'Solicitud de afiliación registrada';
                    $afiliation_description_noti = 'Tu solicitud ha sido enviada exitosamente';
                  
                    //Notificar usuario
                    $user->notify(new MembershipRequestNotification(
                        $afiliation_title_noti,
                        $afiliation_description_noti,
                        $membership,
                        $user
                    ));

                    //Notificar moderadores
                    //Se obtiene a todos los moderadores activos para notificar las publicaciones realizadas
                    $moderator_role = Role::where('slug', 'moderador')->first();
                    $moderators = $moderator_role->users()
                        ->wherePivot('state', true)->get();

                    Notification::send(
                        $moderators,
                        new MembershipRequestNotification(
                                'Nueva Solicitud de Afiliación', //título de la notificación
                                $user->getFullName() . ' ha solicitado afiliación', //descripción de la notificación
                                $membership, // post que almacena la notificación
                                $user //morador que reportó el problema social
                            )
                    );
                        
                    $user_devices = OnesignalNotification::getUserDevices($user->id);
                    if (!is_null($user_devices) && count($user_devices) > 0) {
                        //Enviar notification al usuario en especifico
                        OnesignalNotification::sendNotificationByPlayersID(
                            $afiliation_title_noti,
                            $afiliation_description_noti,
                            [],
                            $user_devices
                        );
                    }
                 
                    //Retornar Token
                    $token = $jwtAuth->getToken($user->email);
                    return $this->sendResponse(200, 'Afiliacion Solicitada Correctamente', ['token' => $token]);
                }
                //Si no existe envio error
                return $this->sendError(404, 'Usuario no existe', ['user' => 'usuario no existe']);
            }
            //Si validacion falla envio error
            return $this->sendError(400, 'Los datos enviados no son válidos', $validatorAfiliation->messages());
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Actualiza la contraseña de un usuario
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */

    public function changePassword(Request $request)
    {
        $jwtAuth = new JwtAuth();
        $token_decoded = $request->get('token');
        try {
            // Obtener los datos de la request
            $validatorPassword = Validator::make($request->all(), [
                                    'password' => 'required|confirmed',
                                ]);
            // Verificar si el validador falla
            if (!$validatorPassword->fails()) {
                $passwordNew = $request->get('password');
                $user = User::findById($token_decoded->user->id)->first();
                //Verificar si el usuario existe
                if (!is_null($user)) {
                    $user->password = password_hash($passwordNew, PASSWORD_BCRYPT);
                    $user->save();
                    $token = $jwtAuth->getToken($user->email);
                    return $this->sendResponse(200, 'Credenciales Actualizadas', ['token' => $token]);
                }
                //Si no existe envio un error
                return $this->sendError(404, 'Usuario no existe', ['user' => 'usuario no existe']);
            }
            //Si falla el validador envio error
            return $this->sendError(404, 'Usuario no existe', ['user' => 'usuario no existe']);
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Retorna el listado de emergencias de un usuario
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */

    public function getEmergenciesByUser($user_id)
    {
        try {
            //TODO: php artisan config:clear
            $user = User::findById($user_id)->first();
            $category = Category::slug(Config::get('siu_config.categorias')['emergencias'])->first();
            //Verifico si existe el usuario
            if (is_null($user)) {
                return $this->sendError(404, 'no existe el usuario', ['user' => 'no existe la emergencia']);
            }
            //Verificar si existe la categoria
            if (is_null($category)) {
                return $this->sendError(404, 'La categoria solicitada es incorrecta', ['category' => 'La categoria solicitada no existe']);
            }
            //Si existe retorno el listado de emergencias
            $social_problems = Post::categoryId($category->id)
                                ->userId($user_id)
                                ->orderBy('id', 'desc')
                                ->with(['resources', 'category', 'subcategory', 'reactions', 'user'])
                                ->simplePaginate(10);
            return $this->sendPaginateResponse(200, 'success', $social_problems->toArray());
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Actualiza el Avatar de un Usuario
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */

    public function changeAvatar(Request $request)
    {
        try {
            $utils = new Utils();
            $jwtAuth = new JwtAuth();
            $token_decoded = $request->get('token');
            // Verificar que me llegue imagen del avatar
            $validatorAvatar = Validator::make($request->all(), [
                                    'avatar' => ['required','image', 'mimes:png,jpeg,jpg'],
                                ]);
            // Verificar si el validador falla
            if (!$validatorAvatar->fails()) {
                // Obtener los datos de la request
                $user = User::findById($token_decoded->user->id)->first();
                //Validar si el usuario existe
                if (!is_null($user)) {
                    $imageApi = new ApiImages();
                    $image_name = $imageApi->saveUserImageApi($request->avatar, null, true);
                    $user->avatar = $image_name;
                    $user->save();
                    $token = $jwtAuth->getToken($user->email);
                    return $this->sendResponse(200, 'Avatar Actualizado', ['token' => $token]);
                }
                //si no existe el usuario envio error
                return $this->sendError(404, 'Usuario no existe', ['user' => 'usuario no existe']);
            }
            //Si validacion falla envio error
            return $this->sendError(400, 'Datos no Válidos', $validatorAvatar->messages());
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Edita el Perfil Social de un Usuario
    * @param \Illuminate\Http\Request $request
    *
    * @return array
    */

    public function editProfile(ApiEditUserProfile $request)
    {
        try {
            // Obtener los datos de la request
            $jwtAuth = new JwtAuth();
            $token_decoded = $request->get('token');
            $validated = $request->validated();
            $user = User::findById($token_decoded->user->id)->first();
            //Validar si el usuario existe
            if (!is_null($user)) {
                $user->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'number_phone' => $request->number_phone
                    ]);
                $token = $jwtAuth->getToken($request->email);
                return $this->sendResponse(200, 'Usuario Actualizado', ['token' => $token]);
            }
            //Si no existe envio error
            return $this->sendError(404, 'Usuario no existe', ['user' => 'usuario no existe']);
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Retorna el listado de dispositivos de un usuario
    * @param integer $id
    *
    * @return array
    */

    public function devicesXUser($id)
    {
        try {
            $user = User::findById($id)->with(['devices'])->first();
            //Validar si el usuario existe
            if (!is_null($user)) {
                $devices = $user->devices;
                return $this->sendResponse(200, 'success', $devices);
            }
            //Si no tiene mandar error
            return $this->sendError(404, 'usuario no existe', ['user' => 'usuario no existe']);
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Retorna el listado de Perfiles Sociales de un Usuario
    * @param integer $id
    *
    * @return array
    */

    public function socialProfilesXUser($id)
    {
        try {
            $user = User::findById($id)->with(['social_profiles'])->first();
            //Validar si existe el usuario
            if (!is_null($user)) {
                $social_profiles = $user->social_profiles;
                return $this->sendResponse(200, 'success', $social_profiles);
            }
            //Si no existe enviar un error
            return $this->sendError(404, 'usuario no encontrado', ['user' => 'usuario no encontrado']);
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }

    /**
    * Retorna el listado de directivos
    *
    * @return array
    */

    public function getDirectives()
    {
        try {
            $users = User::rolDirectivo()->with(['roles' => function ($query) {
                $query->where('slug', 'directivo');
            }
                                , 'position'])->get();
            $users = $users->makeHidden('password');
            //Verificar si existen directivos
            if (!is_null($users)) {
                return $this->sendResponse(200, 'success', $users);
            }
            //Si no existe enviar mensaje error
            return $this->sendError(404, 'no existen directivos', ['category' => 'no existen directivos']);
        } catch (Exception $e) {
            return $this->sendError(500, 'error', ['server_error' => $e->getMessage()]);
        }
    }


    /**
    * Retorna las notificaciones de un usuario
    * @param integer $user_id
    *
    * @return array
    */
    public function getNotificationsUser(Request $request, $user_id)
    {
        try {
            //Verificar si existe el usuario
            $user = User::findById($user_id)->first();
            $filterSize =  ($request->get('size')) ? intval($request->get('size')): 20;
            $filterByTitle = ($request->get('title')) ? $request->get('title'): '';
            $filterUnreaded = $request->get('unreaded') ?? '';

            if (is_null($user)) {
                return $this->sendError(404, 'no existe el usuario', ['notifications' => 'no existe el usuario']);
            }

            $queryset = $user->notifications();
            
            if ($filterByTitle != '') {
                $queryset = $queryset->where('data->title', 'LIKE', "%$filterByTitle%");
            }

            if ($filterUnreaded != '') {
                if ($filterUnreaded == "1") {
                    $queryset = $queryset->whereRaw('read_at is not null');
                } else {
                    $queryset = $queryset->whereRaw('read_at is null');
                }
            }
            // dd($queryset->toSql());
            $notifications = $queryset->orderBy('created_at', 'DESC')->simplePaginate($filterSize)->toArray();
            return $this->sendPaginateResponse(200, 'Notificaciones obtenidas correctamente', $notifications);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    public function getMembresiasUser($user_id)
    {
        try {
            $user = User::findById($user_id)->first();
            $membresias = $user->memberships()->get();
            return $this->sendResponse(200, 'success', $membresias);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Marcar una notificacion como leida
     * @param integer $user_id
     *
     * @return array
     */
    public function markReadNotificationsUser(Request $request, $user_id)
    {
        try {
            $validatorNotifications = Validator::make($request->all(), [
                "notification_id" => ['required', 'string'],
            ]);
            if ($validatorNotifications->fails()) {
                return $this->sendError(400, "Datos no válidos", $validatorNotifications->messages());
            }
            $user = User::findById($user_id)->first();
            if (is_null($user)) {
                return $this->sendError(404, 'no existe el usuario', ['notifications' => 'no existe el usuario']);
            }
            $notification = $user->notifications()->find($request->notification_id);
            if ($notification) {
                $notification->read_at = date('Y-m-d H:i:s');
                $notification->save();
                return $this->sendResponse(204, 'Notificaciones obtenidas correctamente', []);
            } else {
                return $this->sendError(404, 'no existe la notificacion', ['notifications' => 'no existe la notificacion']);
            }
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }
}
