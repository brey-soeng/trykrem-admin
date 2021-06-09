<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JwtController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [JwtController::class, 'login']);
    Route::post('/register', [JwtController::class, 'register']);
    Route::post('/logout', [JwtController::class, 'logout']);
    Route::post('/refresh', [JwtController::class, 'refresh']);
    Route::get('/user-profile', [JwtController::class, 'userProfile']);
});
