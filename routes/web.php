<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// RUTAS PÚBLICAS
Route::get('/home', 'HomeController@index')->name('home');

//rutas de autenticación
Auth::routes(['register'=>false]);

Route::get('logout', function () {
    return abort(404);
});

//RUTAS PRIVADAS
//Se debe autenticar el usuario para ingresar a las siguientes rutas
Route::middleware(['auth'])->group(function(){
    //ROLES
    Route::get('roles', 'RoleController@index')->name('roles.index')->middleware('can:roles.index');
    Route::get('roles/create', 'RoleController@create')->name('roles.create')->middleware('can:roles.create');
    Route::post('roles/store', 'RoleController@store')->name('roles.store')->middleware('can:roles.create');
    Route::get('roles/{role}', 'RoleController@show')->name('roles.show')->middleware('can:roles.show');
    Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit')->middleware('can:roles.edit');
    Route::put('roles/{role}', 'RoleController@update')->name('roles.update')->middleware('can:roles.edit');
    Route::delete('roles/{role}', 'RoleController@destroy')->name('roles.destroy')->middleware('can:roles.destroy');
});
