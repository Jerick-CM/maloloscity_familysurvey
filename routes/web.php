<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\PDFController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

Route::get('/', [AuthenticatedSessionController::class, 'create']);

Route::group(['middleware' => ['permission:Access-Page-SurveyForm']], function () {
    Route::get('/forms-create',  [App\Http\Controllers\FamilySurveyController::class, 'handleCreate'])->middleware(['auth', 'verified'])->name('forms-create');
});

Route::group(['middleware' => ['permission:Access-Page-SurveyForm']], function () {
    Route::get('/forms-edit/{id}',  [App\Http\Controllers\FamilySurveyController::class, 'handleEdit'])->middleware(['auth', 'verified'])->name('forms-edit');
});

Route::group(['middleware' => ['permission:Access-Page-SurveyForm']], function () {
    Route::get('/forms-index',  [App\Http\Controllers\FamilySurveyController::class, 'index'])->middleware(['auth', 'verified'])->name('forms-index');
});

Route::group(['prefix' => 'request/familysurvey', 'middleware' => ['throttle:500,1']], function () {

    Route::get('/dashboard/get', [\App\Http\Controllers\Api\DashboardController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\FamilySurveyController::class, 'show']);

    Route::post('/', [\App\Http\Controllers\Api\FamilySurveyController::class, 'store']);

    Route::post('/getSelectfield', [\App\Http\Controllers\Api\FamilySurveyController::class, 'getSearchfield']);
    Route::post('/update/{id}', [\App\Http\Controllers\Api\FamilySurveyController::class, 'update']);
    Route::post('/delete/{id}', [\App\Http\Controllers\Api\FamilySurveyController::class, 'destroy']);
});

Route::group(['prefix' => 'request/isf', 'middleware' => ['throttle:500,1']], function () {

    Route::get('/{id}', [\App\Http\Controllers\Api\ISFController::class, 'show']);
    Route::post('/', [\App\Http\Controllers\Api\ISFController::class, 'store']);
    Route::post('/fetch', [\App\Http\Controllers\Api\ISFController::class, 'fetch']);
    Route::post('/update/{id}', [\App\Http\Controllers\Api\ISFController::class, 'update']);
    Route::post('/delete/{id}', [\App\Http\Controllers\Api\ISFController::class, 'destroy']);

    Route::post('/getSelectfield', [\App\Http\Controllers\Api\ISFController::class, 'getSearchfield']);
});

Route::group(['prefix' => 'table/familysurvey', 'middleware' => ['throttle:500,1']], function () {
    Route::post('/fetch', [\App\Http\Controllers\FamilySurveyController::class, 'fetch']);
});

Route::group(['prefix' => 'report/', 'middleware' => ['permission:Action Download SurveyForm', 'throttle:500,1']], function () {
    Route::get('/pdf/{barangay}', [PDFController::class, 'survey_report']);
});

Route::group(['prefix' => 'report_isf/', 'middleware' => ['permission:Action Download ISF', 'throttle:500,1']], function () {
    Route::get('/pdf/{barangay}', [PDFController::class, 'survey_report_isf']);
});

/* permission */
Route::post('/users/updatePermissions', [\App\Http\Controllers\Api\UserController::class, 'updatePermissions']);

Route::group(['middleware' => ['permission:Access-Page-Dashboard']], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});

/* User Page */

Route::group(['prefix' => 'user', 'middleware' => ['permission:Access-Page-User', 'throttle:500,1']], function () {

    /* index */
    Route::get('/', function () {
        return Inertia::render('User/Index');
    })->middleware(['auth', 'verified'])->name('user');
    /* create */
    Route::get('/create', function () {
        return Inertia::render('User/Create');
    })->middleware(['auth', 'verified'])->name('user-create');
    /* edit */

    Route::get('/edit/{id}', [App\Http\Controllers\UserController::class, 'handleEdit'])->middleware(['auth', 'verified'])->name('user-edit');

    /* changepassword */
    Route::get('/change-password/{id}', function () {
        return Inertia::render('User/ChangePassword');
    })->middleware(['auth', 'verified'])->name('user-change-password');

    /* admin reset password */
    Route::get('/reset-password/{id}', function () {
        return Inertia::render('User/ResetPassword');
    })->middleware(['auth', 'verified'])->name('user-reset-password');
});

Route::group(['prefix' => 'isf', 'middleware' => ['permission:Access-Page-ISF', 'throttle:500,1']], function () {

    /* index */
    Route::get('/', [App\Http\Controllers\ISFController::class, 'index'])->middleware(['auth', 'verified'])->name('isf-index');

    /* create */
    Route::get('/create', [App\Http\Controllers\ISFController::class, 'handleISFCreate'])->middleware(['auth', 'verified'])->name('isf-create');

    /* view */
    Route::get('/view/{id}', function () {
        return Inertia::render('ISF/View');
    })->middleware(['auth', 'verified'])->name('isf-view');

    /* edit */
    Route::get('/edit/{id}', [App\Http\Controllers\ISFController::class, 'handleISFEdit'])->middleware(['auth', 'verified'])->name('isf-edit');

    /* export */
    Route::post('/export', [\App\Http\Controllers\Api\ISFController::class, 'export'])->name('isf-export');
});

/* User Page */
Route::group(['prefix' => 'logs', 'middleware' => 'throttle:500,1'], function () {
    /* index */
    Route::get('/', function () {
        return Inertia::render('Logs/Index');
    })->middleware(['auth', 'verified'])->name('logs');
});

Route::group(['middleware' => ['permission:Action Settings Roles', 'throttle:500,1']], function () {
    Route::get('/roles', function () {
        return Inertia::render('Roles');
    })->middleware(['auth', 'verified'])->name('roles');
});

/* Msc */
Route::get('phpinfo', function () {
    echo phpinfo();
});

Route::group(['prefix' => 'cstm', 'middleware' => 'throttle:500,1'], function () {
    /* roles */
    Route::get('roles', [\App\Http\Controllers\Api\RoleController::class, 'index']);
    /* checklist */
    Route::get('/checklists', [\App\Http\Controllers\Api\ChecklistController::class, 'fetch']);
    Route::post('/checklists/all', [\App\Http\Controllers\Api\ChecklistController::class, 'update_checkist']);
    /* itineraries */
    Route::post('/itineraries', [\App\Http\Controllers\Api\ItineraryController::class, 'store']);
    Route::post('/itineraries/add_business', [\App\Http\Controllers\Api\ItineraryController::class, 'add_business']);
    Route::post('/itineraries/fetch/{id}', [\App\Http\Controllers\Api\ItineraryController::class, 'fetch']);
});


Route::group(['prefix' => 'pwd', 'middleware' => ['permission:Access-Page-PWD', 'throttle:500,1']], function () {

    /* index */
    Route::get('/', [App\Http\Controllers\PwdListController::class, 'index'])->middleware(['auth', 'verified'])->name('pwd-index');

    /* create */
    Route::get('/create', [App\Http\Controllers\PwdListController::class, 'handleCreate'])->middleware(['auth', 'verified'])->name('pwd-create');

    /* edit */
    Route::get('/edit/{id}', [App\Http\Controllers\PwdListController::class, 'handleEdit'])->middleware(['auth', 'verified'])->name('pwd-edit');

    /* view */
    Route::get('/view/{id}', [App\Http\Controllers\PwdListController::class, 'handleView'])->middleware(['auth', 'verified'])->name('pwd-view');

    /* fetch */
    Route::post('/fetch', [\App\Http\Controllers\Api\PwdListController::class, 'fetch'])->name('pwd-fetch');

    /* selectmultiple */
    Route::post('/multiselect', [\App\Http\Controllers\Api\PwdListController::class, 'getSearchfield'])->name('pwd-multiselect');

    /* create record */
    Route::post('/', [\App\Http\Controllers\Api\PwdListController::class, 'store'])->name('pwd-store');

    /* get record */
    Route::get('/get/{id}', [\App\Http\Controllers\Api\PwdListController::class, 'show'])->name('pwd-request-edit');

    /* update record */
    Route::post('/update/{id}', [\App\Http\Controllers\Api\PwdListController::class, 'update'])->name('pwd-request-update');

    /* delete record */
    Route::post('/delete/{id}', [\App\Http\Controllers\Api\PwdListController::class, 'destroy'])->name('pwd-request-delete');

    /* export */
    Route::post('/export', [\App\Http\Controllers\Api\PwdListController::class, 'export'])->name('pwd-export');

});

Route::group(['prefix' => 'soloparent', 'middleware' => ['permission:Access-Page-SoloParent', 'throttle:500,1']], function () {
    /* fetch */
    Route::post('/fetch', [\App\Http\Controllers\Api\SoloParentListController::class, 'fetch'])->name('soloparent-fetch');

    /* index */
    Route::get('/', [App\Http\Controllers\SoloParentListController::class, 'index'])->middleware(['auth', 'verified'])->name('soloparent-index');

    /* create */
    Route::get('/create', [App\Http\Controllers\SoloParentListController::class, 'handleCreate'])->middleware(['auth', 'verified'])->name('soloparent-create');

    /* view */
    Route::get('/view/{id}', [App\Http\Controllers\SoloParentListController::class, 'handleView'])->middleware(['auth', 'verified'])->name('soloparent-view');

    /* edit */
    Route::get('/edit/{id}', [App\Http\Controllers\SoloParentListController::class, 'handleEdit'])->middleware(['auth', 'verified'])->name('soloparent-edit');

    /* create record */
    Route::post('/', [\App\Http\Controllers\Api\SoloParentListController::class, 'store'])->name('soloparent-store');

    /* selectmultiple */
    Route::post('/multiselect', [\App\Http\Controllers\Api\SoloParentListController::class, 'getSearchfield'])->name('soloparent-multiselect');

    /* delete record */
    Route::post('/delete/{id}', [\App\Http\Controllers\Api\SoloParentListController::class, 'destroy'])->name('soloparent-request-delete');

    /* get record */
    Route::get('/get/{id}', [\App\Http\Controllers\Api\SoloParentListController::class, 'show'])->name('soloparent-request-edit');

    /* update record */
    Route::post('/update/{id}', [\App\Http\Controllers\Api\SoloParentListController::class, 'update'])->name('soloparent-request-update');

    /* export */
    Route::post('/export', [\App\Http\Controllers\Api\SoloParentListController::class, 'export'])->name('soloparent-export');

});

require __DIR__ . '/auth.php';
