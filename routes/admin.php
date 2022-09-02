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
Route::apiResource('companies', \App\Http\Controllers\Api\CompanyController::class);
Route::apiResource('user', \App\Http\Controllers\Api\UserController::class);
Route::apiResource('business', \App\Http\Controllers\Api\BusinessController::class);
Route::apiResource('lineofbusiness', \App\Http\Controllers\Api\LineOfBusinessController::class);
Route::apiResource('itineraries', \App\Http\Controllers\Api\ItineraryController::class);


/*  api custom */

Route::post('business/search', [\App\Http\Controllers\Api\BusinessController::class, 'search']);

Route::post('business/select-search', [\App\Http\Controllers\Api\BusinessController::class, 'select_search']);
Route::post('business/get_lineofbusiness', [\App\Http\Controllers\Api\BusinessController::class, 'get_lineofbusiness']);
Route::post('business/get_qtr_paid', [\App\Http\Controllers\Api\BusinessController::class, 'get_qtr_paid']);
Route::post('business/get_selectfield', [\App\Http\Controllers\Api\BusinessController::class, 'get_selectfield']);
Route::post('itineraries/search', [\App\Http\Controllers\Api\ItineraryController::class, 'search']);
Route::post('business/get_by_ids', [\App\Http\Controllers\Api\BusinessController::class, 'get_by_ids']);

Route::get('business/location/{location}', [\App\Http\Controllers\Api\BusinessController::class, 'strategic_location']);

Route::get('test', function (Request $request) {
    echo 'hello';
    echo $request->user();
});
