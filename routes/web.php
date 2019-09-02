<?php
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;

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
    Route::get('members/filters/{option}', 'DirectiveController@filters')->name('members.filters')->middleware('can:members.index');
    Route::get('members/create', 'DirectiveController@create')->name('members.create')->middleware('can:members.create');
    Route::post('members/store', 'DirectiveController@store')->name('members.store')->middleware('can:members.create');
    Route::get('members/{member}', 'DirectiveController@show')->name('members.show')->middleware('can:members.show');
    Route::get('members/{member}/edit', 'DirectiveController@edit')->name('members.edit')->middleware('can:members.edit');
    Route::put('members/{member}', 'DirectiveController@update')->name('members.update')->middleware('can:members.edit');
    Route::delete('members/{member}', 'DirectiveController@destroy')->name('members.destroy')->middleware('can:members.destroy');
    Route::get('search','SearchController@search')->name('search')->middleware('can:members.index');
    //CARGOS
    Route::get('positions', 'PositionController@index')->name('positions.index')->middleware('can:positions.index');
    Route::get('positions/create', 'PositionController@create')->name('positions.create')->middleware('can:positions.create');
    Route::post('positions/store', 'PositionController@store')->name('positions.store')->middleware('can:positions.create');
    Route::get('positions/{position}/edit', 'PositionController@edit')->name('positions.edit')->middleware('can:positions.edit');
    Route::put('positions/{position}', 'PositionController@update')->name('positions.update')->middleware('can:positions.edit');
    Route::delete('positions/{position}', 'PositionController@destroy')->name('positions.destroy')->middleware('can:positions.destroy');
    // Route::get('positions/{member}', 'PositionController@show')->name('positions.show')->middleware('can:positions.show');
    Route::get('positions/{member}', function () {
        return abort(404);
    });
    //PROFILE
    Route::get('profile','ProfileController@index')->name('profile');
    Route::put('profile/avatar','ProfileController@changeAvatar')->name('profile.avatar');
    Route::get('profile/avatar', function () {
        return abort(404);
    });
    Route::put('profile/data','ProfileController@changePersonalData')->name('profile.data');
    Route::get('profile/data', function () {
        return abort(404);
    });
    Route::put('profile/password','ProfileController@changePassword')->name('profile.password');
    Route::get('profile/password', function () {
        return abort(404);
    });
    //VECINOS-MORADORES
    Route::get('neighbors', 'NeighborController@index')->name('neighbors.index')->middleware('can:neighbors.index');
    Route::get('neighbors/create', 'NeighborController@create')->name('neighbors.create')->middleware('can:neighbors.create');
    Route::post('neighbors/store', 'NeighborController@store')->name('neighbors.store')->middleware('can:neighbors.create');
    Route::get('neighbors/{neighbor}', 'NeighborController@show')->name('neighbors.show')->middleware('can:neighbors.show');
    Route::get('neighbors/{neighbor}/edit', 'NeighborController@edit')->name('neighbors.edit')->middleware('can:neighbors.edit');
    Route::put('neighbors/{neighbor}', 'NeighborController@update')->name('neighbors.update')->middleware('can:neighbors.edit');
    Route::delete('neighbors/{neighbor}', 'NeighborController@destroy')->name('neighbors.destroy')->middleware('can:neighbors.destroy');
});
