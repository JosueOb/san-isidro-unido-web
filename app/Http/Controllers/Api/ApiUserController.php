<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Helpers\ApiImages;
use App\Helpers\JwtAuth;
use App\Helpers\Utils;
use App\Http\Controllers\Api\ApiBaseController;
use App\Post;
use App\Role;
use App\Rules\Api\Base64FormatImage;
use App\Rules\Api\ProviderData;
use App\Rules\Api\ValidarCedula;
use App\SocialProfile;
use App\User;
use App\MembershipRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiDeviceController;

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
            'first_name' => "required|string|max:255",
            'last_name' => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email"
        ];
        $this->validacionesFormLogin = [
            "email" => 'email|required|max:255',
        ];
        $this->validacionesDevice = [
            "description" => 'string|required',
            "phone_id" => 'string|required|max:100',
            "phone_model" => 'string|required|max:100',
        ];
        $this->roles = Config::get('siu_config.roles');
        $this->categories = Config::get('siu_config.categorias');
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
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Setea el proveedor en las validaciones de formulario
     * @param string $provider
     * @param array $form
     *
     * @return void
     */
    public function checkProviderValidation($provider, $form)
    {
        $fieldSecureToCheck = ($provider === "formulario") ? "password" : "social_id";
        switch ($form) {
            case 'login':
                $this->validacionesFormLogin[$fieldSecureToCheck] = "required|string";
                break;
            case 'register':
                $this->validacionesFormRegistro[$fieldSecureToCheck] = "required|string";
                break;
            default:
                break;
        }
    }

    /**
     * Registra a un usuario y retorna el token del usuario
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function register(Request $request)
    {
        $utils = new Utils();
        try {
            // Crear el validador del proveedor de datos esta presente y es de los permitidos
            $validatorProvider = Validator::make($request->all(), [
                "provider" => ['required', 'string', new ProviderData],
            ]);
            $rolInvitado = Role::slug($this->roles['invitado'])->first();
            //Recoger los datos de la peticion
            $inputRegister = $request->all();
            // Verificar si fallo la validacion del proveedor de datos
            if (!$validatorProvider->fails()) {
                // Agregar validacion dependiendo del proveedor de datos
                $this->checkProviderValidation($inputRegister['provider'], 'register');
                //Crear el validador de los datos necesarios para guardar el usuario
                $validatorJSONData = Validator::make($request->all(), $this->validacionesFormRegistro);
                //Verificar si fallo la validacion de los datos necesarios para guardar el usuario                     
                if (!$validatorJSONData->fails()) {
                    //Validar dispositivo
                    $device = $request->get('device', null);
                    if($device){
                        $validatorDevice = Validator::make($device, $this->validacionesDevice);
                        if($validatorDevice->fails()){
                            $device = null;
                        }
                    }

                    return $this->createUser(
                        $inputRegister['first_name'] ?? '',
                        $inputRegister['last_name'] ?? '',
                        $inputRegister['email'] ?? '',
                        $inputRegister['provider'] ?? '',
                        $inputRegister['password'] ?? '',
                        $inputRegister['social_id'] ?? '',
                        $inputRegister['avatar'] ?? '',
                        $inputRegister['device'] ?? null);
                }
                //Enviar error datos enviados
                // $mensajesError = $validatorJSONData->messages();
                $firstError = $validatorJSONData->errors()->first();
                return $this->sendError(400, $firstError,$validatorJSONData->messages());
            }
            //Enviar error proveedor invalido
            return $this->sendError(400, "Proveedor Inválido", $validatorProvider->messages());
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el Servidor", ['server_error' => $e->getMessage()]);
        }
    }

    private function registerNormalUser($first_name, $last_name, $email, $provider, $password, $avatar, $device = null) {
        $rolInvitado = Role::slug($this->roles['invitado'])->first();
        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = ($provider === 'formulario' && $password) ? password_hash($password, PASSWORD_DEFAULT) : null;
        $user->state = true;
        $user->avatar = $avatar;
        $user->save();
        $user->roles()->attach($rolInvitado->id, [
            'state' => 1,
        ]);
        if($device){
            $apiDeviceController = new ApiDeviceController;
            $apiDeviceController->saveDevice(
                (array_key_exists("phone_id",$device)) ? $device['phone_id']: '', 
                (array_key_exists("phone_model",$device)) ? $device['phone_model']: '', 
                (array_key_exists("phone_platform",$device)) ? $device['phone_platform']: 'Modelo Generico',
                (array_key_exists("description",$device)) ? $device['description']: '', $user->id);
        }
        return $user;
    }
    /*CREAR USUARIO EN EL API */
    public function createUser($first_name, $last_name, $email, $provider, $password, $social_id, $avatar, $device = null) {
        $utils = new Utils();
        //Crear usuario normal
        if ($provider == 'formulario') {
            $this->registerNormalUser(
                $first_name,$last_name,$email,$provider,$password, $avatar, $device);
        } else {
            //Crear usuario social
            $userExist = User::email($email)->first();
            if (!$userExist) {
               
                $user = $this->registerNormalUser($first_name,$last_name,$email,$provider,$password, $avatar, $device);
            } else {
                $user = User::email($email)->first();
                if($device){
                    $apiDeviceController = new ApiDeviceController;
                    $apiDeviceController->saveDevice(
                        (array_key_exists("phone_id",$device)) ? $device['phone_id']: '', 
                        (array_key_exists("phone_model",$device)) ? $device['phone_model']: '', 
                        (array_key_exists("phone_platform",$device)) ? $device['phone_platform']: 'Modelo Generico',
                        (array_key_exists("description",$device)) ? $device['description']: '', $user->id);
                }
            }
            // Verificar si debe guardar el perfil social
            $socialProfileExists = $utils->socialProfileExists($provider, $email);
            //Agregar perfil Social
            if (!$socialProfileExists) {
                $this->createUserSocial($social_id, $user->id, $provider);
            }
        }
        //Obtener usuario para enviarlo de vuelta al FrontEnd
        $jwtAuth = new JwtAuth();
        $token = $jwtAuth->getToken($email, null);
        return $this->sendResponse(200, "Usuario Registrado Correctamente", $token);
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
    public function login(Request $request)
    {
        try {
            //Recoger los datos de la peticion
            $requestData = $request->all();
            // VALIDAR PROVEEDOR DATOS
            $validatorProvider = Validator::make($request->all(), [
                "provider" => ['required', 'string', new ProviderData],
            ]);
            // Verificar si el validador falla
            if (!$validatorProvider->fails()) {
                //VALIDAR CAMPOS REQUEST
                $this->checkProviderValidation($requestData['provider'], 'login');
                $validatorJSONData = Validator::make($requestData, $this->validacionesFormLogin);
                // SI VALIDACION FALLA, MANDAR ERROR
                if (!$validatorJSONData->fails()) {
                    //Devolver Token o datos
                    $jwtAuth = new JwtAuth();
                    
                    //Validar dispositivo
                    $device = $request->get('device', null);
                    if($device){
                        $validatorDevice = Validator::make($device, $this->validacionesDevice);
                        if($validatorDevice->fails()){
                            $device = null;
                        }
                    }
                    //Verificar si se quiere obtener los datos del Token
                    $returnDataOrToken = ($request->has('getToken')) ? true : null;
                    //Mandar Pass or SocialID dependiendo Proveedor Login
                    $passOrSocialID = ($requestData['provider'] === 'formulario') ? $requestData['password'] : $requestData['social_id'];
                    $userExist = User::where('email', $requestData['email'])->first();
                    //Si usuario no existe, creo el usuario
                    if (!$userExist && $requestData['provider'] = 'formulario') {
                        return $this->createUser(
                            $requestData['first_name'] ?? '',
                            $requestData['last_name'] ?? '',
                            $requestData['email'] ?? '',
                            $requestData['provider'] ?? '',
                            $requestData['password'] ?? '',
                            $requestData['social_id'] ?? '',
                            $requestData['avatar'] ?? '',
                            $device);
                    }else{
                        //Verificar si credenciales son validas
                        if ($jwtAuth->singIn($requestData['email'], $passOrSocialID, $requestData['provider'])) {
                            //guardar device si existe
                            $user = User::email($requestData['email'])->first();
                            if($device){
                                $apiDeviceController = new ApiDeviceController;
                                $apiDeviceController->saveDevice(
                                    (array_key_exists("phone_id",$device)) ? $device['phone_id']: '', 
                                    (array_key_exists("phone_model",$device)) ? $device['phone_model']: '', 
                                    (array_key_exists("phone_platform",$device)) ? $device['phone_platform']: 'Modelo Generico',
                                    (array_key_exists("description",$device)) ? $device['description']: '', $user->id);
                            }
                            $token = $jwtAuth->getToken($requestData['email'], $returnDataOrToken);    
                            return $this->sendResponse(200, "Login Correcto", $token);
                        }else{     
                            //Si falla login retorno error
                            switch (strtolower($requestData['provider'])) {
                                case 'facebook':
                                    return $this->sendError(400, "No has asociado tu cuenta de facebook, registrate por favor", ['user' => 'No has asociado tu cuenta de facebook, registrate por favor']);
                                    break;
                                case 'google':
                                    return $this->sendError(400, "No has asociado tu cuenta de google, registrate por favor", ['user' => 'No has asociado tu cuenta de google, registrate por favor']);
                                    break;
                                default:
                                    return $this->sendError(400, "Usuario y/o Contraseña Inválida", ['user' => 'Usuario y/o Contraseña Incorrecto']);
                                    break;
                            }
                        }
                    }
                }
                // $firstError = $validatorJSONData->errors()->first();
                //Si validador falla retorno error
                return $this->sendError(400, $firstError->errors()->first(), $validatorJSONData->messages());
            }
            //Si validador falla retorno error
            return $this->sendError(400, "Proveedor no válido", $validatorProvider->messages());
        } catch (Exception $e) {
            return $this->sendError(500, "Error en el Servidor", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Verica la validez de un token
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
            return $this->sendResponse(200, "error", $tokenResponse);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
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
        $token_decoded = $request->get('token');
        try {
            //password_confirmation
            $validatorPassword = Validator::make($request->all(), [
                "basic_service_image" => ['required', 'string', new Base64FormatImage],
                "cedula" => ["required", "string", new ValidarCedula],
            ]);
            $image_service_b64 = $request->get('basic_service_image');
            $cedula = $request->get('cedula');
            // Verificar si el validador falla
            if (!$validatorPassword->fails()) {
                $user = User::findById($token_decoded->user->id)->first();
                //Validar si existe el usuario
                if (!is_null($user)) {
                    $imageApi = new ApiImages();
                    $image_name = $imageApi->saveAfiliationImageApi($image_service_b64);
                    $user->basic_service_image = $image_name;
                    $user->cedula = $cedula;
                    $user->save();
                    //Guardar solicitud de afiliación
                    $request = new MembershipRequest();
                    $request->status = 'pendiente_aprobacion';
                    $request->comment = "El Usuario $user->first_name ha solicitado la afiliación al barrio";
                    $request->user_id = $user->id;
                    $request->save();
                    //Retornar Token
                    $token = $jwtAuth->getToken($user->email);
                    return $this->sendResponse(200, "Afiliacion Solicitada Correctamente", ['token' => $token]);
                }
                //Si no existe envio error
                return $this->sendError(404, "Usuario no existe", ['user' => "usuario no existe"]);
            }
            //Si validacion falla envio error
            return $this->sendError(400, "Los datos enviados no son válidos", $validatorPassword->messages());
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
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
                "password" => 'required|confirmed',
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
                    return $this->sendResponse(200, "Credenciales Actualizadas", ['token' => $token]);
                }
                //Si no existe envio un error
                return $this->sendError(404, "Usuario no existe", ['user' => "usuario no existe"]);
            }
            //Si falla el validador envio error
            return $this->sendError(404, "Usuario no existe", ['user' => "usuario no existe"]);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
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
            $category = Category::slug($this->categories['emergencias'])->first();
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
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
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
            $validatorPassword = Validator::make($request->all(), [
                "avatar" => ['required', 'string', new Base64FormatImage],
            ]);
            // Verificar si el validador falla
            if (!$validatorPassword->fails()) {
                // Obtener los datos de la request
                $image_avatar_b64 = $request->get('avatar');
                $user = User::findById($token_decoded->user->id)->first();
                //Validar si el usuario existe
                if (!is_null($user)) {
                    $imageApi = new ApiImages();
                    $image_name = $imageApi->saveAfiliationImageApi($image_avatar_b64);
                    $user->avatar = $image_name;
                    $user->save();
                    $token = $jwtAuth->getToken($user->email);
                    return $this->sendResponse(200, "Avatar Actualizado", ['token' => $token]);
                }
                //si no existe el usuario envio error
                return $this->sendError(404, "Usuario no existe", ['user' => "usuario no existe"]);
            }
            //Si validacion falla envio error
            return $this->sendError(400, "Datos no Válidos", $validatorPassword->messages());
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }

    /**
     * Edita el Perfil Social de un Usuario
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function editProfile(Request $request)
    {
        $jwtAuth = new JwtAuth();
        $token_decoded = $request->get('token');

        try {
            // Obtener los datos de la request
            $validatorEditProfile = Validator::make($request->all(), [
                "first_name" => 'required|string',
                "last_name" => 'required|string',
                "email" => 'required|string|email',
                "number_phone" => 'nullable|string',
            ]);
            // Verificar si el validador falla
            if (!$validatorEditProfile->fails()) {
                $user_update = [
                    "first_name" => $request->get('first_name'),
                    "last_name" => $request->get('last_name'),
                    "email" => $request->get('email'),
                    "number_phone" => $request->get('number_phone'), //debe ir tal cual la request para que actualice correctamente
                ];
                // return $this->sendDebugResponse([[$user_update], [$request->all()]]);
                $user = User::findById($token_decoded->user->id)->first();
                //Validar si el usuario existe
                if (!is_null($user)) {
                    $user->update($user_update);
                    $token = $jwtAuth->getToken($user_update['email']);
                    return $this->sendResponse(200, "Usuario Actualizado", ['token' => $token]);
                }
                //Si no existe envio error
                return $this->sendError(404, "Usuario no existe", ['user' => "usuario no existe"]);
            }
            //Si falla la validacion envio un error
            return $this->sendError(400, "Datos no válidos", $validatorEditProfile->messages());
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
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
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
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
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
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
            }, 'position'])->get();
            $users = $users->makeHidden('password');
            //Verificar si existen directivos
            if (!is_null($users)) {
                return $this->sendResponse(200, 'success', $users);
            }
            //Si no existe enviar mensaje error
            return $this->sendError(404, 'no existen directivos', ['category' => 'no existen directivos']);
        } catch (Exception $e) {
            return $this->sendError(500, "error", ['server_error' => $e->getMessage()]);
        }
    }
}
