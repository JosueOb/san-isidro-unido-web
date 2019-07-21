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
Auth::routes(['register'=>false]);

Route::get('logout', function () {
    return abort(404);
});

Route::get('/home', 'HomeController@index')->name('home');
