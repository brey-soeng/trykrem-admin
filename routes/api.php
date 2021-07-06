<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExceptionErrorController;
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


Route::middleware(['lang','cors'])->prefix('auth')->name('admin')->group(function () {
    Route::get('/broadcast',function (){
        broadcast(new \App\Events\WebSocketEvent());
    });
    Route::post('login', [LoginController::class, 'login']);
    Route::post('refresh', [LoginController::class, 'refresh']);
    Route::post('logout', [LoginController::class, 'logout']);
    Route::middleware(['jwt.role:admin','jwt.auth'])->group(function () {
        // Get user info
        Route::post('/user', [LoginController::class, 'me']);
        Route::middleware(['auth:admin'])->group(function () {
            //exception
            Route::post('exception/logs', [ExceptionErrorController::class, 'logs'])->middleware('permission:exceptionError.exceptionErrors');
            Route::post('exception/log/files', [ExceptionErrorController::class, 'files'])->middleware('permission:reset.password');
            Route::post('exception/log/file', [ExceptionErrorController::class, 'file'])->middleware('permission:exceptionError.logFiles');
            Route::post('exception/amended', [ExceptionErrorController::class, 'amended'])->middleware('permission:exceptionError.amended');
            //Roles
            Route::post('/roles', [RoleController::class, 'index'])->middleware('permission:roles');
            Route::post('role/create', [RoleController::class, 'store'])->middleware('permission:roles.create');
            Route::post('role/update', [RoleController::class, 'update'])->middleware('permission:roles.update');
            Route::post('role/delete', [RoleController::class, 'destroy'])->middleware('permission:roles.delete');
            Route::post('role/syncPermissions', [RoleController::class, 'syncPermissions'])->middleware('permission:role.syncPermissions');
            Route::post('role/syncRoles', [RoleController::class, 'syncRoles'])->middleware('permission:role.syncRoles');
        });
    });
});


