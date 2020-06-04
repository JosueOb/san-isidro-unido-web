<?php
/************** Rutas de la API **************/
use Illuminate\Support\Facades\Route;

// DB::listen(function($e){
//     dump($e->sql);
// });

// Rutas GET
Route::group(['prefix' => 'v1'], function () {
    //Grupo Servicios Publicos
    Route::group(['prefix' => 'servicios-publicos'], function () {
        Route::get('/', 'Api\ApiPublicServiceController@index');
        Route::get('/{id}', 'Api\ApiPublicServiceController@detail')->where('id', '[0-9]+');
        Route::get('/categorias', 'Api\ApiPublicServiceController@getCategories');
        Route::get('/categorias/{slug}', 'Api\ApiPublicServiceController@filterByCategory');
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
        Route::get('/{id}', 'Api\ApiUserController@detail');
        Route::get('/{id}/dispositivos', 'Api\ApiUserController@devicesXUser');
        Route::get('/{id}/perfiles-sociales', 'Api\ApiUserController@socialProfilesXUser');
        Route::get('/{id}/emergencias', 'Api\ApiUserController@getEmergenciesByUser');
        Route::get('/{id}/notificaciones', 'Api\ApiUserController@getNotificationsUser');
    });
});

// Rutas POST
Route::group(['prefix' => 'v1'], function () {
    // Registrar un usuario
    Route::post('registro', "Api\ApiUserController@register")
    ->middleware(['api.user_exists']);
    //Loguear un Usuario
    Route::post('login', "Api\ApiUserController@login")
    ->middleware(['api.user_exists']);
    // Verificar Token
    Route::post('verificar-token', "Api\ApiUserController@checkToken");
    // Crear una Emergencia
    Route::post('emergencias', "Api\ApiPostController@createEmergency")
    ->middleware(['api.user_auth', 'api.user_active', 'base64Image']);
    //Crear un Problema Social
    Route::post('problemas-sociales', "Api\ApiPostController@createSocialProblem")->middleware(['api.user_auth', 'api.user_active', 'base64Image']);
    //Crear un detalle de tipo Likes, Asistencias
    Route::post('detalles', "Api\ApiReactionController@create")
        ->middleware(['api.user_auth', 'api.user_active']);
    // Grupo Emergencias
    Route::group(['prefix' => 'emergencias'], function () {
        //Aceptar atender una emergencia
        Route::post('/atender', "Api\ApiPostController@atenderEmergencia")
        ->middleware(['api.user_auth', 'api.user_active']);
        //Rechazar emergencia
        Route::post('/rechazar', "Api\ApiPostController@rechazarEmergencia")
        ->middleware(['api.user_auth', 'api.user_active']);
    });

    // Grupo Usuarios
    Route::group(['prefix' => 'usuarios'], function () {
        Route::post('/cambiar-avatar', "Api\ApiUserController@changeAvatar")
        ->middleware(['api.user_auth', 'api.user_active', 'base64Image']);
        Route::post('/solicitar-afiliacion', "Api\ApiUserController@requestAfiliation")
        ->middleware(['api.user_auth', 'api.user_active', 'base64Image']);
        Route::post('/dispositivos', "Api\ApiDeviceController@save")
        ->middleware(['api.user_auth', 'api.user_active']);
    });
});

// Rutas PATCH
Route::group(['prefix' => 'v1'], function () {
    //Grupo de Usuarios
    Route::group(['prefix' => "usuarios"], function () {
        Route::patch('/cambiar-contrasenia', "Api\ApiUserController@changePassword")
            ->middleware(['api.user_auth', 'api.user_active']);
        Route::patch('/editar', "Api\ApiUserController@editProfile")
            ->middleware(['api.user_auth', 'api.user_active']);
    });
});

//Rutas Delete
Route::group(['prefix' => "v1"], function () {
    //Eliminar Detalle Publicacion
    Route::delete('detalles/{id}', "Api\ApiReactionController@delete")
        ->middleware(['api.user_auth', 'api.user_active']);
    //Grupo Usuarios
    Route::group(['prefix' => "usuarios"], function () {
        Route::delete('/perfiles-sociales/{profile_id}', "Api\ApiSocialProfileController@delete")
            ->middleware(['api.user_auth', 'api.user_active']);
        Route::delete('/dispositivos/{device_id}', "Api\ApiDeviceController@delete")
            ->middleware(['api.user_auth', 'api.user_active']);
        Route::delete('/dispositivos/logout/{device_phone_id}', "Api\ApiDeviceController@deleteByPhoneId")
            ->middleware(['api.user_auth', 'api.user_active']);
        Route::delete('/{id}/notificaciones', 'Api\ApiUserController@markReadNotificationsUser')
        ->middleware(['api.user_auth', 'api.user_active']);
    });
});

//Ruta Defecto cuando no se encuentra alguna ruta de la Api
Route::fallback(function () {
    return response()->json([
        'message' => 'La ruta solicitada no esta disponible',
        'code' => 404
    ], 404);
});
