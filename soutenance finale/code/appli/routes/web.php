<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AssessController;
use App\Http\Controllers\FileController;
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

Route::get('/', [HomeController::class, 'welcome'])
    ->name('welcome');

/**
 * Route for the deconnection of the users.
 */
Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/**
 * Route that manages user authentication.
 */
Route::post('/{type}/check', [AuthController::class, 'check'])
    ->name('check');

/**
 * Route that manages assessment of {assignation_id} save and then redirecting to {route_name}.
 */
Route::post('/assessment/save/{assignation_id}/{route_name}', [AssessController::class, 'save'])
    ->name('save');

/**
 * Route that writes files on serv and download them in local.
 */
Route::post('/dashboard/export/{content_type}', [FileController::class, 'export'])
    ->name('export');

/**
 * Route that adds new experts or new assessments from a local file to the DB.
 */
Route::post('/dashboard/add/{add_type}', [FileController::class, 'add'])
    ->name('add');


Route::group(['middleware' => ['AuthCheck']], function () {
    /**
     * Route for the connection of the users. The type is expert or admin.
     */
    Route::get('/{type}/login', [HomeController::class, 'login'])
        ->name('login');
    /**
     * Route for the dashboard.
     */
    Route::get('/dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');
    /**
     * Route for the assessment page.
     */
    Route::get('/assessment', [HomeController::class, 'assessment'])
        ->name('assessment');
});
