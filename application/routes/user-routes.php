<?php

use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\Redis\RedisController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('/v1/dewtech')->group(function () {

    Route::post('/login', [AuthUserController::class, 'login']);

    // Rotas protegidas pelo middleware
    Route::middleware(['auth:api'])->group(function () {

        // Rota de Logout
        Route::post('logout',  [AuthUserController::class, 'logout']);

        // Rota para Refresh Token
        Route::post('refreshtoken',  [AuthUserController::class, 'refreshToken']);

        // (Opcional) Rota para validar token, se você realmente precisar dela
        Route::post('validateToken', [AuthUserController::class, 'validateToken']);
    });

    Route::prefix('/redis')->group(function () {
        Route::post('/', [RedisController::class, 'store']);
        Route::get('/{id}', [RedisController::class, 'show']);
        Route::delete('/{id}', [RedisController::class, 'destroy']);
    });

    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
});
