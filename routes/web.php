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
    Route::get('roles/{role}', 'RoleController@show')->name('roles.show')->middleware('can:roles.show');
    Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit')->middleware('can:roles.edit');
    Route::put('roles/{role}', 'RoleController@update')->name('roles.update')->middleware('can:roles.edit');
    //DIRECTIVA
    Route::get('members', 'DirectiveController@index')->name('members.index')->middleware('can:members.index');
    Route::get('members/create', 'DirectiveController@create')->name('members.create')->middleware('can:members.create');
    Route::post('members/store', 'DirectiveController@store')->name('members.store')->middleware('can:members.create');
    Route::get('members/{user}', 'DirectiveController@show')->name('members.show')->middleware('can:members.show');
    Route::get('members/{user}/edit', 'DirectiveController@edit')->name('members.edit')->middleware('can:members.edit');
    Route::put('members/{user}', 'DirectiveController@update')->name('members.update')->middleware('can:members.edit');
    Route::delete('members/{user}', 'DirectiveController@destroy')->name('members.destroy')->middleware('can:members.destroy');
    Route::get('members/filters/{option}', 'DirectiveController@filters')->name('members.filters')->middleware('can:members.index');
    Route::get('search/members','SearchController@searchMembers')->name('search.members')->middleware('can:members.index');
    //CARGOS
    Route::get('positions', 'PositionController@index')->name('positions.index')->middleware('can:positions.index');
    Route::get('positions/create', 'PositionController@create')->name('positions.create')->middleware('can:positions.create');
    Route::post('positions/store', 'PositionController@store')->name('positions.store')->middleware('can:positions.create');
    Route::get('positions/{position}/edit', 'PositionController@edit')->name('positions.edit')->middleware('can:positions.edit');
    Route::put('positions/{position}', 'PositionController@update')->name('positions.update')->middleware('can:positions.edit');
    Route::delete('positions/{position}', 'PositionController@destroy')->name('positions.destroy')->middleware('can:positions.destroy');
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
    Route::get('neighbors/{user}', 'NeighborController@show')->name('neighbors.show')->middleware('can:neighbors.show');
    Route::get('neighbors/{user}/edit', 'NeighborController@edit')->name('neighbors.edit')->middleware('can:neighbors.edit');
    Route::put('neighbors/{user}', 'NeighborController@update')->name('neighbors.update')->middleware('can:neighbors.edit');
    Route::delete('neighbors/{user}', 'NeighborController@destroy')->name('neighbors.destroy')->middleware('can:neighbors.destroy');
    Route::get('neighbors/filters/{option}', 'NeighborController@filters')->name('neighbors.filters')->middleware('can:neighbors.index');
    Route::get('search/neighbors','SearchController@searchNeighbors')->name('search.neighbors')->middleware('can:neighbors.index');
    //INFORMES
    Route::get('reports','ReportController@index')->name('reports.index')->middleware('can:reports.index');
    Route::get('reports/create','ReportController@create')->name('reports.create')->middleware('can:reports.create');
    Route::post('reports/store', 'ReportController@store')->name('reports.store')->middleware('can:reports.create');
    Route::get('reports/{report}', 'ReportController@show')->name('reports.show')->middleware('can:reports.show');
    Route::get('reports/{report}/edit', 'ReportController@edit')->name('reports.edit')->middleware('can:reports.edit');
    Route::put('reports/{report}', 'ReportController@update')->name('reports.update')->middleware('can:reports.edit');
    Route::delete('reports/{report}', 'ReportController@destroy')->name('reports.destroy')->middleware('can:reports.destroy');
    Route::get('reports/filters/{option}', 'ReportController@filters')->name('reports.filters')->middleware('can:reports.index');
    Route::get('search/reports','SearchController@searchReports')->name('search.reports')->middleware('can:reports.index');
    //Servicios Públicos
    Route::get('public-service', 'PublicServiceController@index')->name('publicServices.index'); 
    Route::get('public-service/create', 'PublicServiceController@create')->name('publicServices.create');
});
