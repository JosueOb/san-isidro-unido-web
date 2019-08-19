<?php
use Illuminate\Auth\Events\Verified;

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

//rutas de autenticación
Auth::routes(['register'=>false,'verify'=>true]);
// RUTAS PÚBLICAS

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');


Route::get('logout', function () {
    return abort(404);
});

//RUTAS PRIVADAS
//Se debe autenticar el usuario para ingresar a las siguientes rutas
Route::middleware(['auth','verified'])->group(function(){
    //ROLES
    Route::get('roles', 'RoleController@index')->name('roles.index')->middleware('can:roles.index');
    Route::get('roles/create', 'RoleController@create')->name('roles.create')->middleware('can:roles.create');
    Route::post('roles/store', 'RoleController@store')->name('roles.store')->middleware('can:roles.create');
    Route::get('roles/{role}', 'RoleController@show')->name('roles.show')->middleware('can:roles.show');
    Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit')->middleware('can:roles.edit');
    Route::put('roles/{role}', 'RoleController@update')->name('roles.update')->middleware('can:roles.edit');
    Route::delete('roles/{role}', 'RoleController@destroy')->name('roles.destroy')->middleware('can:roles.destroy');
    //DIRECTIVA
    Route::get('members', 'DirectiveController@index')->name('members.index')->middleware('can:members.index');
    Route::get('members/create', 'DirectiveController@create')->name('members.create')->middleware('can:members.create');
    Route::post('members/store', 'DirectiveController@store')->name('members.store')->middleware('can:members.create');
    Route::get('members/{member}', 'DirectiveController@show')->name('members.show')->middleware('can:members.show');
    Route::get('members/{member}/edit', 'DirectiveController@edit')->name('members.edit')->middleware('can:members.edit');
    Route::put('members/{member}', 'DirectiveController@update')->name('members.update')->middleware('can:members.edit');
    Route::delete('members/{member}', 'DirectiveController@destroy')->name('members.destroy')->middleware('can:members.destroy');
});
