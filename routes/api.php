<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*  apiResource */

Route::apiResource('business', \App\Http\Controllers\Api\BusinessController::class);
Route::apiResource('lineofbusiness', \App\Http\Controllers\Api\LineOfBusinessController::class);
Route::apiResource('itineraries', \App\Http\Controllers\Api\ItineraryController::class);
Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
Route::apiResource('checklists', \App\Http\Controllers\Api\ChecklistController::class);
Route::apiResource('logs', \App\Http\Controllers\Api\LogsController::class);

/*  api custom */

Route::group(['prefix' => 'cstm', 'middleware' => 'throttle:500,1'], function () {

    /* itineraries business pivot  */
    Route::get('/itineraries/gethistoricalbusiness/{itinerary_business_id}/{bussiness_id}', [\App\Http\Controllers\Api\ItineraryController::class, 'get_business_history']);
    Route::get('/itineraries/getbusinesshistory/{itinerary_business_id}/{bussiness_id}', [\App\Http\Controllers\Api\ItineraryController::class, 'getbusinesshistory']);
    Route::get('/itineraries/business_start/{itinerary_business_id}/{bussiness_id}', [\App\Http\Controllers\Api\ItineraryController::class, 'business_start']);

    /* itineraries */
    Route::get('/itineraries/checkvalues/{buss_id}/{itin_id}', [\App\Http\Controllers\Api\ItineraryController::class, 'checkvalues']);
    Route::get('/itineraries/validate_start/{buss_id}/{itin_id}', [\App\Http\Controllers\Api\ItineraryController::class, 'itinerary_start']);
    Route::get('/itineraries/checklists/checklist', [\App\Http\Controllers\Api\ItineraryController::class, 'checklists']);
    Route::get('/itineraries/user/{userid}', [\App\Http\Controllers\Api\ItineraryController::class, 'index_user']);
    Route::get('/itineraries/pull/{id}', [\App\Http\Controllers\Api\ItineraryController::class, 'pull']);
    Route::get('/itineraries/pull', [\App\Http\Controllers\Api\ItineraryController::class, 'pull']);
    Route::post('/itineraries/delete_selected', [\App\Http\Controllers\Api\ItineraryController::class, 'destroy_selected']);

    Route::post('/itineraries/start', [\App\Http\Controllers\Api\ItineraryController::class, 'add_start']);
    Route::post('/itineraries/finish', [\App\Http\Controllers\Api\ItineraryController::class, 'add_finish']);
    Route::post('/itineraries/search', [\App\Http\Controllers\Api\ItineraryController::class, 'search']);
    Route::post('/itineraries/add_business', [\App\Http\Controllers\Api\ItineraryController::class, 'add_business']);
    Route::post('/itineraries/itin_busi', [\App\Http\Controllers\Api\ItineraryController::class, 'delete_pivot']);

    /* revision */
    Route::post('/itineraries/completebusiness_checklist', [\App\Http\Controllers\Api\ItineraryController::class, 'completebusiness_checklist']);

    /* businesses */
    Route::get('/business/export_selected_business', [\App\Http\Controllers\Api\BusinessController::class, 'strategic_location']);
    Route::get('/business/location/{location}', [\App\Http\Controllers\Api\BusinessController::class, 'strategic_location']);
    Route::get('/business/itinerary', [\App\Http\Controllers\Api\BusinessController::class, 'index_itinerary']);

    Route::post('/business/search', [\App\Http\Controllers\Api\BusinessController::class, 'search']);
    Route::post('/business/select-search', [\App\Http\Controllers\Api\BusinessController::class, 'select_search']);
    Route::post('/business/get_lineofbusiness', [\App\Http\Controllers\Api\BusinessController::class, 'get_lineofbusiness']);
    Route::post('/business/get_qtr_paid', [\App\Http\Controllers\Api\BusinessController::class, 'get_qtr_paid']);
    Route::post('/business/get_selectfield', [\App\Http\Controllers\Api\BusinessController::class, 'get_selectfield']);
    Route::post('/business/get_by_ids', [\App\Http\Controllers\Api\BusinessController::class, 'get_by_ids']);

    /* users */
    Route::get('/users/{id}', [\App\Http\Controllers\Api\UserController::class, 'index_user']);
    Route::put('/users/changepassword/{id}', [\App\Http\Controllers\Api\UserController::class, 'change_password']);
    Route::put('/users/resetpassword/{id}', [\App\Http\Controllers\Api\UserController::class, 'reset_password']);
    Route::delete('/users/{id}/{user_id}', [\App\Http\Controllers\Api\UserController::class, 'delete_user']);
    Route::post('/users/fetch', [\App\Http\Controllers\Api\UserController::class, 'fetch']);

    /* checklist */
    // Route::post('/checklists/single', [\App\Http\Controllers\Api\ChecklistController::class, 'single']);
    Route::get('/getchecklistdata/{itinerary_business_id}', [\App\Http\Controllers\Api\ItineraryController::class, 'getchecklistdata']);
    Route::post('/checklists/all', [\App\Http\Controllers\Api\ChecklistController::class, 'updateall']);

    /* dashboard */
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index']);
    Route::post('/user/export', [\App\Http\Controllers\Api\UserController::class, 'exportdata']);
    Route::post('/business/export', [\App\Http\Controllers\ExportsController::class, 'business_export']);
    Route::post('/itinerary/export', [\App\Http\Controllers\ExportsController::class, 'itinerary_export']);
    Route::post('/ib/export', [\App\Http\Controllers\ExportsController::class, 'itinerary_business_export']);

    /* exports */
    Route::post('/itinerary/export_selected', [\App\Http\Controllers\ExportsController::class, 'export_selected']);
    Route::post('/itinerary/export_selected_with_business', [\App\Http\Controllers\ExportsController::class, 'export_selected_with_business']);
    Route::post('/business/export_selected_business', [\App\Http\Controllers\ExportsController::class, 'export_selected_business']);

    /* deletes */
    Route::delete('/itinerary/{id}/{user_id}', [\App\Http\Controllers\Api\ItineraryController::class, 'destroy_with_log']);
    Route::delete('/business/{id}/{user_id}', [\App\Http\Controllers\Api\BusinessController::class, 'destroy_with_log']);

    /* logs */
    Route::post('/logs/search', [\App\Http\Controllers\Api\LogsController::class, 'search']);
    Route::post('/logs/fetch', [\App\Http\Controllers\Api\LogsController::class, 'fetch']);
    Route::post('/business/fetch', [\App\Http\Controllers\Api\BusinessController::class, 'fetch']);
    Route::post('/itineraries/fetch', [\App\Http\Controllers\Api\ItineraryController::class, 'fetch']);
    Route::post('/itineraries/fetch/{id}', [\App\Http\Controllers\Api\ItineraryController::class, 'fetch']);

    Route::get('/roles', [\App\Http\Controllers\Api\RoleController::class, 'index_edit']);
    Route::get('/roles/user_edit', [\App\Http\Controllers\Api\RoleController::class, 'index_user_edit']);
    Route::post('/roles/update_all', [\App\Http\Controllers\Api\RoleController::class, 'update_all']);

    /* permission */
    Route::post('/users/updatePermissions', [\App\Http\Controllers\Api\UserController::class, 'updatePermissions']);
});
