<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::prefix('V1')->group(function () {
Route::controller(ModuleController::class)->prefix('module')->group(function () {
    //Module routes
    Route::get('/list',  'index');
    Route::post('create', 'create');
    Route::post('update/{id}', 'update');
    Route::get('delete/{id}',  'destroy');
});
//permissions routes
Route::controller(PermissionController::class)->prefix('permission')->group(function () {
    Route::get('/list', 'index');
    Route::post('create', 'create');
    Route::post('update/{id}', 'update');
    Route::get('delete/{id}',  'destroy');
});
//role routes
Route::controller(RoleController::class)->prefix('role')->group(function () {
    Route::get('/list', 'index');
    Route::post('create', 'create');
    Route::post('update/{id}', 'update');
    Route::get('delete/{id}',  'destroy');
});
//user routes
Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::get('/list', 'index');
    Route::post('create', 'create');
    Route::post('update/{id}', 'update');
    Route::get('delete/{id}',  'destroy');
});
// });
