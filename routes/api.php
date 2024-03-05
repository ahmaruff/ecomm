<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function(){
    Route::post('register',[\App\Http\Controllers\Auth\AuthController::class, 'register']);
    Route::post('login',[\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::post('logout',[\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::post('refresh',[\App\Http\Controllers\Auth\AuthController::class, 'refresh']);
    Route::post('me',[\App\Http\Controllers\DashboardController::class, 'me']);
});
