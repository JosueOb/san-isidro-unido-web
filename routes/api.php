<?php
/************** Rutas de la API **************/
use Illuminate\Support\Facades\Route;

// Rutas GET
Route::group(['prefix' => 'v1'], function () {
    //Grupo Servicios Publicos
    Route::group(['prefix' => 'servicios-publicos'], function () {
        Route::get('/', 'Api\ApiPublicServiceController@index');
        Route::get('/{id}', 'Api\ApiPublicServiceController@detail')->where('id', '[0-9]+');
        Route::get('/categorias', 'Api\ApiPublicServiceController@getCategories');
        Route::get('/categoria/{slug}', 'Api\ApiPublicServiceController@filterByCategory');
    });
    //Grupo Publicaciones
    Route::group(['prefix' => 'publicaciones'], function () {
        Route::get('/', 'Api\ApiPostController@index');
        Route::get('/{id}', 'Api\ApiPostController@detail')->where('id', '[0-9]+');
    });
    //Grupo Categorias
    Route::group(['prefix' => 'categorias'], function () {
        Route::get('/', 'Api\ApiCategoryController@index');
        Route::get('/{id}', 'Api\ApiCategoryController@detail')->where('id', '[0-9]+');
        Route::get('/{slug}', 'Api\ApiCategoryController@filterCategories');
        Route::get('/{slug}/subcategorias', 'Api\ApiCategoryController@getSubcategory');
    });
    // Ruta Directivos
    Route::get('directivos', 'Api\ApiUserController@getDirectives');
    // Grupo Usuarios
    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/', 'Api\ApiUserController@index');
        Route::get('/{id}', 'Api\ApiTestController@detail');
        Route::get('/{id}/roles', 'Api\ApiTestController@rolesXUser');
        Route::get('/{id}/dispositivos', 'Api\ApiUserController@devicesXUser');
        Route::get('/{id}/perfiles-sociales', 'Api\ApiUserController@socialProfilesXUser');
        Route::get('/{id}/emergencias', 'Api\ApiUserController@getEmergenciesByUser');
        Route::get('/{id}/notificaciones', 'Api\ApiMobileNotificationController@getNotificationsUser');
    });
    //Servir las Imagenes
    Route::get('imagenes/{filename}', "Api\ApiImageController@getImageB64");
});

// Rutas POST
Route::group(['prefix' => 'v1'], function () {
    // Registrar un usuario
    Route::post('registro', "Api\ApiUserController@register");
    //Loguear un Usuario
    Route::post('login', "Api\ApiUserController@login");
    // Verificar Token
    Route::post('verificar-token', "Api\ApiUserController@checkToken");
    // Crear una Emergencia
    Route::post('emergencias', "Api\ApiPostController@createEmergency")
    ->middleware(['api.user_auth', 'api.permission:morador']);
    //Crear un Problema Social
    Route::post('problemas-sociales', "Api\ApiPostController@createSocialProblem")
    ->middleware(['api.user_auth', 'api.permission:morador']);
    //Crear un detalle de tipo Likes, Asistencias
    Route::post('detalles', "Api\ApiDetailController@create")
        ->middleware(['api.user_auth', 'api.permission:morador,policia']);
    //Añadir un dispositivo
    Route::post('usuarios/dispositivos', "Api\ApiDeviceController@save")
        ->middleware(['api.user_auth', 'api.permission:morador,policia']);
});

// Rutas PATCH
Route::group(['prefix' => 'v1'], function () {
    //Grupo de Usuarios
    Route::group(['prefix' => "usuarios"], function () { 
        Route::patch('/solicitar-afiliacion', "Api\ApiUserController@requestAfiliation")
            ->middleware(['api.user_auth', 'api.permission:invitado']);
        Route::patch('/cambiar-contrasenia', "Api\ApiUserController@changePassword")
            ->middleware(['api.user_auth', 'api.permission:morador,policia']);
        Route::patch('/cambiar-avatar', "Api\ApiUserController@changeAvatar")
            ->middleware(['api.user_auth', 'api.permission:morador,policia']);
        Route::patch('/editar', "Api\ApiUserController@editProfile")
            ->middleware(['api.user_auth', 'api.permission:morador,policia']);
    });
});

//Rutas Delete
Route::group(['prefix' => "v1"], function () {
    //Eliminar Detalle Publicacion
    Route::delete('detalles/{id}', "Api\ApiDetailController@delete")
        ->middleware(['api.user_auth', 'api.permission:morador,policia']);
    //Grupo Usuarios
    Route::group(['prefix' => "usuarios"], function () {
        Route::delete('/perfiles-sociales/{profile_id}', "Api\ApiSocialProfileController@delete")
            ->middleware(['api.user_auth', 'api.permission:morador,policia']);
        Route::delete('/dispositivos/{device_id}', "Api\ApiDeviceController@delete")
            ->middleware(['api.user_auth', 'api.permission:morador,policia']);
        Route::delete('/dispositivos/logout/{device_phone_id}', "Api\ApiDeviceController@deleteByPhoneId")
            ->middleware(['api.user_auth', 'api.permission:morador,policia']);
    });
});

//Rutas PRUEBAS
Route::group(['prefix' => "v1"], function () {
    //Probar si se recibe correctamente la imagen
    Route::get('test-index', 'Api\ApiTestController@indexTest');
    Route::post('imagen', "Api\ApiTestController@receiveImage");
    Route::get('check-pass', "Api\ApiTestController@CheckPass");
    Route::post('check-cors', 'Api\ApiTestController@CheckCors');
    Route::get('mapUser', "Api\ApiTestController@mapUser");
    Route::get('encriptar-password/{pass}', 'Api\ApiTestController@EncriptarPass');
    Route::get('attachRoles', "Api\ApiTestController@attachRoles");
    //Guzzzle
    Route::get('guzzle-get', "Api\ApiTestController@getGuzzleRequest");
    Route::get('guzzle-noti', "Api\ApiTestController@postOnesignalGuzzle");
    //Otras pruebas
    Route::get('ramiro-devices', "Api\ApiTestController@createDevicesTest");
    Route::get('ramiro-profiles', "Api\ApiTestController@createSocialProfilesTest");
    // Listar los Roles del Sistema
    Route::get('roles', 'Api\ApiRoleController@index');
    Route::get('roles/{id}', 'Api\ApiRoleController@detail');
    Route::get('pdf/{filename}', 'Api\ApiTestController@servePDF');
    Route::get('eloquent/modelname', 'Api\ApiTestController@getNameModel');
});

//Ruta Defecto cuando no se encuentra alguna ruta de la Api 
Route::fallback(function () {
    return response()->json([
        'message' => 'La ruta que buscas no existe'
    ], 404);
});
