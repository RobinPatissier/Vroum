<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Routes pour l'inscription et la connexion
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Route pour la déconnexion
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

// Routes protégées par JWT
Route::middleware('auth:api')->group(function () {
    Route::apiResource('trips', TripController::class);
    Route::apiResource('users', UserController::class);
});

Route::middleware('auth:api')->group(function () {
    Route::post('trips', [TripController::class, 'store']);
    // Ajoutez d'autres routes protégées par JWT ici si nécessaire
});

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
