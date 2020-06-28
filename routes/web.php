<?php

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
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

/*
|--------------------------------------------------------------------------
| HomePage
|--------------------------------------------------------------------------
*/

Route::get('/', 'LandingController@index')->name('landing');
/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Auth::routes(['register' => false, 'verify' => true]);

/*
|--------------------------------------------------------------------------
| Email Verification
|--------------------------------------------------------------------------
*/
Route::get('verifiedMail/{id}', function (Request $request) {
    if (!$request->hasValidSignature()) {
        abort(401);
    }
    return view('auth.verifiedMail');
})->name('verifiedMail');

Route::get('verifiedMailWeb/{id}', function (Request $request) {
    if (!$request->hasValidSignature()) {
        abort(401);
    }
    return view('auth.verifiedMailWeb');
})->name('verifiedMailWeb');

/*
|--------------------------------------------------------------------------
| Private Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'logout'])->group(function () {
    //HOMEPAGE
    Route::get('/home', 'HomeController@index')->name('home');
    //PROFILE
    Route::get('profile', 'ProfileController@index')->name('profile');
    Route::put('profile/avatar', 'ProfileController@changeAvatar')->name('profile.avatar');
    Route::get('profile/avatar', function () {
        return abort(404);
    });
    Route::put('profile/data', 'ProfileController@changePersonalData')->name('profile.data');
    Route::get('profile/data', function () {
        return abort(404);
    });
    Route::put('profile/password', 'ProfileController@changePassword')->name('profile.password');
    Route::get('profile/password', function () {
        return abort(404);
    });
    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */
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
    Route::get('search/members', 'SearchController@members')->name('search.members')->middleware('can:members.index');

    //CARGOS
    Route::get('positions', 'PositionController@index')->name('positions.index')->middleware('can:positions.index');
    Route::get('positions/create', 'PositionController@create')->name('positions.create')->middleware('can:positions.create');
    Route::post('positions/store', 'PositionController@store')->name('positions.store')->middleware('can:positions.create');
    Route::get('positions/{position}/edit', 'PositionController@edit')->name('positions.edit')->middleware('can:positions.edit');
    Route::put('positions/{position}', 'PositionController@update')->name('positions.update')->middleware('can:positions.edit');
    Route::delete('positions/{position}', 'PositionController@destroy')->name('positions.destroy')->middleware('can:positions.destroy');
    Route::get('positions/{position}', function () {
        return abort(404);
    });

    //CATEGORIA
    Route::get('category', 'CategoryController@index')->name('categories.index')->middleware('can:categories.index');
    Route::get('category/{category}/edit', 'CategoryController@edit')->name('categories.edit')->middleware('can:categories.edit');
    Route::put('category/{category}', 'CategoryController@update')->name('categories.update')->middleware('can:categories.edit');
    Route::get('category/{category}', function () {
        return abort(404);
    });

    //SUBCATEGORIA
    Route::get('subcategory', 'SubcategoryController@index')->name('subcategories.index')->middleware('can:subcategories.index');
    Route::get('subcategory/create', 'SubcategoryController@create')->name('subcategories.create')->middleware('can:subcategories.create');
    Route::post('subcategory/store', 'SubcategoryController@store')->name('subcategories.store')->middleware('can:subcategories.create');
    Route::get('subcategory/{subcategory}/edit', 'SubcategoryController@edit')->name('subcategories.edit')->middleware('can:subcategories.edit');
    Route::put('subcategory/{subcategory}', 'SubcategoryController@update')->name('subcategories.update')->middleware('can:subcategories.edit');
    Route::delete('subcategory/{subcategory}', 'SubcategoryController@destroy')->name('subcategories.destroy')->middleware('can:subcategories.destroy');
    Route::get('search/subcategories', 'SearchController@subcategories')->name('search.subcategories')->middleware('can:subcategories.index');


    Route::get('subcategory/{subcategory}', function () {
        return abort(404);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin-Directive
    |--------------------------------------------------------------------------
    */
    //VECINOS-MORADORES
    Route::get('neighbors', 'NeighborController@index')->name('neighbors.index')->middleware('can:neighbors.index');
    Route::get('neighbors/create', 'NeighborController@create')->name('neighbors.create')->middleware('can:neighbors.create');
    Route::post('neighbors/store', 'NeighborController@store')->name('neighbors.store')->middleware('can:neighbors.create');
    Route::get('neighbors/{user}', 'NeighborController@show')->name('neighbors.show')->middleware('can:neighbors.show');
    Route::get('neighbors/{user}/edit', 'NeighborController@edit')->name('neighbors.edit')->middleware('can:neighbors.edit');
    Route::put('neighbors/{user}', 'NeighborController@update')->name('neighbors.update')->middleware('can:neighbors.edit');
    Route::delete('neighbors/{user}', 'NeighborController@destroy')->name('neighbors.destroy')->middleware('can:neighbors.destroy');
    Route::get('search/neighbors', 'SearchController@neighbors')->name('search.neighbors')->middleware('can:neighbors.index');

    /*
    |--------------------------------------------------------------------------
    | Directive-Moderator
    |--------------------------------------------------------------------------
    */
    //INFORMES
    Route::get('reports', 'ReportController@index')->name('reports.index')->middleware('can:reports.index');
    Route::get('reports/create', 'ReportController@create')->name('reports.create')->middleware('can:reports.create');
    Route::post('reports/store', 'ReportController@store')->name('reports.store')->middleware('can:reports.create');
    Route::get('reports/{post}', 'ReportController@show')->name('reports.show')->middleware('can:reports.show');
    Route::get('reports/{post}/edit', 'ReportController@edit')->name('reports.edit')->middleware('can:reports.edit');
    Route::put('reports/{post}', 'ReportController@update')->name('reports.update')->middleware('can:reports.edit');
    Route::delete('reports/{post}', 'ReportController@destroy')->name('reports.destroy')->middleware('can:reports.destroy');
    Route::get('search/reports', 'SearchController@reports')->name('search.reports')->middleware('can:reports.index');

    //SERVICIOS PUBLICOS
    Route::get('public-service', 'PublicServiceController@index')->name('publicServices.index')->middleware('can:publicServices.index');
    Route::get('public-service/create', 'PublicServiceController@create')->name('publicServices.create')->middleware('can:publicServices.create');
    Route::post('public-service/store', 'PublicServiceController@store')->name('publicServices.store')->middleware('can:publicServices.create');
    Route::get('public-service/{publicService}', 'PublicServiceController@show')->name('publicServices.show')->middleware('can:publicServices.show');
    Route::get('public-service/{publicService}/edit', 'PublicServiceController@edit')->name('publicServices.edit')->middleware('can:publicServices.edit');
    Route::put('public-service/{publicService}', 'PublicServiceController@update')->name('publicServices.update')->middleware('can:publicServices.edit');
    Route::delete('public-service/{publicService}', 'PublicServiceController@destroy')->name('publicServices.destroy')->middleware('can:publicServices.destroy');
    Route::get('search/publicServices', 'SearchController@publicServices')->name('search.publicServices')->middleware('can:publicServices.index');

    //EVENTOS
    Route::get('events', 'EventController@index')->name('events.index')->middleware('can:events.index');
    Route::get('events/create', 'EventController@create')->name('events.create')->middleware('can:events.create');
    Route::post('events/store', 'EventController@store')->name('events.store')->middleware('can:events.create');
    Route::get('events/{post}', 'EventController@show')->name('events.show')->middleware('can:events.show');
    Route::get('events/{post}/edit', 'EventController@edit')->name('events.edit')->middleware('can:events.edit');
    Route::put('events/{post}', 'EventController@update')->name('events.update')->middleware('can:events.edit');
    Route::delete('events/{post}', 'EventController@destroy')->name('events.destroy')->middleware('can:events.destroy');
    Route::get('search/events', 'SearchController@events')->name('search.events')->middleware('can:events.index');

    //MODERADOR
    Route::get('moderators/assign', 'ModeratorController@assign')->name('moderators.assign')->middleware('can:moderators.assign');
    Route::get('search/assign', 'SearchController@assign')->name('search.assign')->middleware('can:moderators.assign');
    Route::put('moderators/assign/{user}', 'ModeratorController@storeAssign')->name('moderators.storeAssign')->middleware('can:moderators.assign');
    Route::get('moderators', 'ModeratorController@index')->name('moderators.index')->middleware('can:moderators.index');
    Route::get('search/moderators', 'SearchController@moderators')->name('search.moderators')->middleware('can:moderators.index');
    Route::get('moderators/create', 'ModeratorController@create')->name('moderators.create')->middleware('can:moderators.create');
    Route::post('moderators/store', 'ModeratorController@store')->name('moderators.store')->middleware('can:moderators.create');
    Route::get('moderators/{user}', 'ModeratorController@show')->name('moderators.show')->middleware('can:moderators.show');
    Route::get('moderators/{user}/edit', 'ModeratorController@edit')->name('moderators.edit')->middleware('can:moderators.edit');
    Route::put('moderators/{user}', 'ModeratorController@update')->name('moderators.update')->middleware('can:moderators.edit');
    Route::delete('moderators/{user}', 'ModeratorController@destroy')->name('moderators.destroy')->middleware('can:moderators.destroy');

    //POLICIA
    Route::get('policemen', 'PoliceController@index')->name('policemen.index')->middleware('can:policemen.index');
    Route::get('policemen/create', 'PoliceController@create')->name('policemen.create')->middleware('can:policemen.create');
    Route::post('policemen/store', 'PoliceController@store')->name('policemen.store')->middleware('can:policemen.create');
    Route::get('policemen/{user}', 'PoliceController@show')->name('policemen.show')->middleware('can:policemen.show');
    Route::get('policemen/{user}/edit', 'PoliceController@edit')->name('policemen.edit')->middleware('can:policemen.edit');
    Route::put('policemen/{user}', 'PoliceController@update')->name('policemen.update')->middleware('can:policemen.edit');
    Route::delete('policemen/{user}', 'PoliceController@destroy')->name('policemen.destroy')->middleware('can:policemen.destroy');
    Route::get('search/policemen', 'SearchController@policemen')->name('search.policemen')->middleware('can:policemen.index');

    //API - NOTIFICACIONES
    Route::get('api/notifications/problems', 'NotificationController@api_problems')->name('notifications.problems')->middleware('can:notifications.problems');
    Route::get('api/notifications/emergencies', 'NotificationController@api_emergencies')->name('notifications.emergencies')->middleware('can:notifications.emergencies');
    Route::get('api/notifications/memberships', 'NotificationController@api_memberships')->name('notifications.memberships')->middleware('can:notifications.memberships');

    // LISTAR NOTIFICACIONES
    Route::get('notifications/problems', 'NotificationController@problems')->name('notifications.allProblems')->middleware('can:notifications.problems');
    Route::get('notifications/emergencies', 'NotificationController@emergencies')->name('notifications.allEmergencies')->middleware('can:notifications.emergencies');
    Route::get('notifications/memberships', 'NotificationController@memberships')->name('notifications.allMemberships')->middleware('can:notifications.memberships');

    //PROBLEMAS REPORTADOS
    Route::get('request/socialProblem/{notification}', 'SocialProblemReportController@show')->name('socialProblemReport.show')->middleware('can:notifications.problems');
    Route::get('request/approve/socialProblem/{notification}', 'SocialProblemReportController@approve')->name('socialProblemReport.approve')->middleware('can:socialProblemReports.approveOrReject');
    Route::get('request/reject/socialProblem/{notification}', 'SocialProblemReportController@showReject')->name('socialProblemReport.showReject')->middleware('can:socialProblemReports.approveOrReject');
    Route::post('request/reject/socialProblem/{notification}', 'SocialProblemReportController@reject')->name('socialProblemReport.reject')->middleware('can:socialProblemReports.approveOrReject');

    // EMERGENCIAS REPORTADAS
    Route::get('request/emergency/{notification}', 'EmergencyReportController@show')->name('emergencyReport.show')->middleware('can:notifications.emergencies');
    Route::get('request/publish/emergency/{notification}', 'EmergencyReportController@publish')->name('emergencyReport.publish')->middleware('can:emergencyReport.publish');

    // SOLICITUDES DE AFILIACIÓN
    Route::get('request/membership/{notification}', 'MembershipController@show')->name('membership.show')->middleware('can:notifications.memberships');
    Route::get('request/approve/membership/{notification}', 'MembershipController@approve')->name('membership.approve')->middleware('can:membership.approveOrReject');
    Route::get('request/reject/membership/{notification}', 'MembershipController@showReject')->name('membership.showReject')->middleware('can:membership.approveOrReject');
    Route::post('request/reject/membership/{notification}', 'MembershipController@reject')->name('membership.reject')->middleware('can:membership.approveOrReject');

    //POBLEMA SOCIAL - GESTIÓN DIRECTIVA
    Route::get('socialProblems', 'SocialProblemController@index')->name('socialProblems.index')->middleware('can:socialProblems.index');
    Route::get('socialProblems/{post}', 'SocialProblemController@show')->name('socialProblems.show')->middleware('can:socialProblems.show');
    Route::get('socialProblems/attend/{post}', 'SocialProblemController@attend')->name('socialProblems.attend')->middleware('can:socialProblems.attendOrReject');
    Route::get('socialProblems/reject/{post}', 'SocialProblemController@showReject')->name('socialProblems.showReject')->middleware('can:socialProblems.attendOrReject');
    Route::post('socialProblems/reject/{post}', 'SocialProblemController@reject')->name('socialProblems.reject')->middleware('can:socialProblems.attendOrReject');
    Route::get('search/socialProblems', 'SearchController@socialProblems')->name('search.socialProblems')->middleware('can:socialProblems.index');
    
    // EMERGENCIAS
    Route::get('emergencies', 'EmergencyController@index')->name('emergencies.index')->middleware('can:emergencies.index');
    Route::get('emergencies/{post}', 'EmergencyController@show')->name('emergencies.show')->middleware('can:emergencies.show');
    Route::get('search/emergencies', 'SearchController@emergencies')->name('search.emergencies')->middleware('can:emergencies.index');
});

Route::get('logout', function () {
    return abort(404);
});
