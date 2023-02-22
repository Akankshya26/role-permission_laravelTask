<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    //user routes
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::post('/list', 'index')->middleware('check-access:user,view_access');
        Route::get('edit/{id}', 'edit')->middleware('check-access:user,view_access');
        Route::post('create', 'create')->middleware('check-access:user,add_access');
        Route::post('update/{id}', 'update')->middleware('check-access:user,edit_access');
        Route::post('delete/{id}',  'destroy')->middleware('check-access:user,delete_access');
        Route::get('restore/{id}', 'restore');
    });

    Route::controller(ModuleController::class)->prefix('module')->group(function () {
        //Module routes
        Route::post('/list',  'index')->middleware('check-access:user,view_access');
        Route::post('create', 'create')->middleware('check-access:user,add_access');
        Route::get('edit/{id}', 'edit')->middleware('check-access:user,view_access');
        Route::post('update/{id}', 'update')->middleware('check-access:user,edit_access');
        Route::post('delete/{id}',  'destroy')->middleware('check-access:user,delete_access');
        Route::get('restore/{id}', 'restore');
    });
    //permissions routes
    Route::controller(PermissionController::class)->prefix('permission')->group(function () {
        Route::post('/list', 'index')->middleware('check-access:user,view_access');
        Route::post('create', 'create')->middleware('check-access:user,add_access');
        Route::get('edit/{id}', 'edit')->middleware('check-access:user,view_access');
        Route::post('update/{id}', 'update')->middleware('check-access:user,edit_access');
        Route::post('delete/{id}',  'destroy')->middleware('check-access:user,delete_access');
        Route::get('restore/{id}', 'restore');
    });
    //role routes
    Route::controller(RoleController::class)->prefix('role')->group(function () {
        Route::post('/list', 'index')->middleware('check-access:user,view_access');
        Route::get('edit/{id}', 'edit')->middleware('check-access:user,add_access');
        Route::post('create', 'create')->middleware('check-access:user,view_access');
        Route::post('update/{id}', 'update')->middleware('check-access:user,edit_access');
        Route::post('delete/{id}',  'destroy')->middleware('check-access:user,delete_access');
        Route::get('restore/{id}', 'restore');
    });
});
