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

Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
Route::apiResource('checklists', \App\Http\Controllers\Api\ChecklistController::class);
Route::apiResource('logs', \App\Http\Controllers\Api\LogsController::class);

/*  api custom */

Route::group(['prefix' => 'cstm', 'middleware' => 'throttle:500,1'], function () {

    /* users */
    Route::get('/users/{id}', [\App\Http\Controllers\Api\UserController::class, 'index_user']);
    Route::put('/users/changepassword/{id}', [\App\Http\Controllers\Api\UserController::class, 'change_password']);
    Route::put('/users/resetpassword/{id}', [\App\Http\Controllers\Api\UserController::class, 'reset_password']);
    Route::delete('/users/{id}/{user_id}', [\App\Http\Controllers\Api\UserController::class, 'delete_user']);
    Route::post('/users/fetch', [\App\Http\Controllers\Api\UserController::class, 'fetch']);

    /* dashboard */
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index']);
    Route::post('/user/export', [\App\Http\Controllers\Api\UserController::class, 'exportdata']);

    /* logs */
    Route::post('/logs/search', [\App\Http\Controllers\Api\LogsController::class, 'search']);
    Route::post('/logs/fetch', [\App\Http\Controllers\Api\LogsController::class, 'fetch']);

    Route::get('/roles', [\App\Http\Controllers\Api\RoleController::class, 'index_edit']);
    Route::get('/roles/user_edit', [\App\Http\Controllers\Api\RoleController::class, 'index_user_edit']);
    Route::post('/roles/update_all', [\App\Http\Controllers\Api\RoleController::class, 'update_all']);

});
