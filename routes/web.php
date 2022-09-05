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
use App\Models\User;
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


Route::group(['middleware' => ['permission:Access-Page-Dashboard']], function () {
    Route::get('/forms-create',  [App\Http\Controllers\FamilySurveyController::class, 'handleCreate'])->middleware(['auth', 'verified'])->name('forms-create');
});

Route::group(['middleware' => ['permission:Access-Page-Dashboard']], function () {
    Route::get('/forms-edit/{id}',  [App\Http\Controllers\FamilySurveyController::class, 'handleEdit'])->middleware(['auth', 'verified'])->name('forms-edit');
});

Route::group(['middleware' => ['permission:Access-Page-Dashboard']], function () {
    Route::get('/forms-index',  [App\Http\Controllers\FamilySurveyController::class, 'index'])->middleware(['auth', 'verified'])->name('forms-index');
});

Route::group(['prefix' => 'request/familysurvey', 'middleware' => ['permission:Access-Page-Dashboard', 'throttle:500,1']], function () {
    Route::post('/', [\App\Http\Controllers\Api\FamilySurveyController::class, 'store']);
    Route::post('/fetch', [\App\Http\Controllers\Api\FamilySurveyController::class, 'fetch']);
    Route::post('/getSelectfield', [\App\Http\Controllers\Api\FamilySurveyController::class, 'getSearchfield']);
    Route::get('/{id}', [\App\Http\Controllers\Api\FamilySurveyController::class, 'show']);
    Route::post('/{id}', [\App\Http\Controllers\Api\FamilySurveyController::class, 'update']);
});

// old

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

    Route::get('/edit/{id}',   [App\Http\Controllers\UserController::class, 'handleEdit'])->middleware(['auth', 'verified'])->name('user-edit');

    /* changepassword */
    Route::get('/change-password/{id}', function () {
        return Inertia::render('User/ChangePassword');
    })->middleware(['auth', 'verified'])->name('user-change-password');

    /* admin reset password */
    Route::get('/reset-password/{id}', function () {
        return Inertia::render('User/ResetPassword');
    })->middleware(['auth', 'verified'])->name('user-reset-password');
});


/* Business Page */
Route::group(['prefix' => 'business', 'middleware' => ['permission:Access-Page-Business', 'throttle:500,1']], function () {
    /* index */
    Route::get('/', function () {
        return Inertia::render('Business/Index');
    })->middleware(['auth', 'verified'])->name('business');
    /* edit */
    Route::get('/edit/{id}', [BusinessController::class, 'rewrite'])->middleware(['auth', 'verified'])->name('business-edit');
    /* create */
    Route::get('/create', [BusinessController::class, 'index'])->middleware(['auth', 'verified'])->name('business-create');
});

/* Itinerary Page */
Route::group(['prefix' => 'itinerary', 'middleware' => ['throttle:500,1']], function () {
    /* index */
    Route::get('/',  [App\Http\Controllers\ItineraryController::class, 'index'])->middleware(['auth', 'verified'])->name('itinerary');
    /* edit */
    Route::get('/edit/{id}', function () {
        return Inertia::render('Itinerary/Edit');
    })->middleware(['auth', 'verified'])->name('itinerary-edit');
    /* pull */
    Route::get('/pull/{id}',  [App\Http\Controllers\ItineraryController::class, 'pull'])->middleware(['auth', 'verified'])->name('itinerary-pull');
    /* create */
    Route::get('/create',  [App\Http\Controllers\ItineraryController::class, 'create'])->middleware(['auth', 'verified'])->name('itinerary-create');
});

/* User Page */
Route::group(['prefix' => 'logs', 'middleware' => 'throttle:500,1'], function () {
    /* index */
    Route::get('/', function () {
        return Inertia::render('Logs/Index');
    })->middleware(['auth', 'verified'])->name('logs');
});
Route::group(['middleware' => ['permission:Action Settings Checklist', 'throttle:500,1']], function () {
    Route::get('/checklist', function () {
        return Inertia::render('Checklist');
    })->middleware(['auth', 'verified'])->name('checklist');
});

Route::group(['middleware' => ['permission:Action Settings Roles', 'throttle:500,1']], function () {
    Route::get('/roles', function () {
        return Inertia::render('Roles');
    })->middleware(['auth', 'verified'])->name('roles');
});

/* Pdf */
Route::get('generate-pdf', [PDFController::class, 'generatePDF']);
Route::get('view-pdf/{id}', [PDFController::class, 'view_print_itinerary']);
Route::get('view-checklist/{id}', [PDFController::class, 'view_print_checklist']);

/* Msc */
Route::get('phpinfo', function () {
    echo phpinfo();
});

Route::get('/trace/{hash}', [\App\Http\Controllers\Api\ItineraryController::class, 'trace'])->name('trace');

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


require __DIR__ . '/auth.php';
