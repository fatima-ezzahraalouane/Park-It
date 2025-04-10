<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminStatsController;

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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// route pour recuperer l'utilisateur connecte
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// routes accessibles à tous les utilisateurs authentifiés
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/parkings', [ParkingController::class, 'index']);  // Liste des parkings
    Route::get('/parkings/search', [ParkingController::class, 'search']); // Rechercher parking
    Route::get('/parkings/{id}', [ParkingController::class, 'show']);  // Détails d'un parking
});


// routes reservées aux administrateurs (ajout, modification, suppression)
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::post('/parkings', [ParkingController::class, 'store']); // ajouter un parking
    Route::put('/parkings/{id}', [ParkingController::class, 'update']); // modifier un parking
    Route::delete('/parkings/{id}', [ParkingController::class, 'destroy']); // supprimer un parking
});

// routes pour reservation
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index']); // voir ses reservations
    Route::post('/reservations', [ReservationController::class, 'store']); // reserver une place
    Route::get('/reservations/history', [ReservationController::class, 'history']); // voir l'historique de reservation
    Route::get('/reservations/{id}', [ReservationController::class, 'show']); // voir une réservation specifique
    Route::put('/reservations/{id}', [ReservationController::class, 'update']); // modifier une reservation
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']); // annuler une reservation
});

// route pour les statistiques
Route::middleware(['auth:sanctum', 'is_admin'])->get('/admin/stats', [AdminStatsController::class, 'stats']);