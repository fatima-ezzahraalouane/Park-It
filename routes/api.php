<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();

    Route::get('/parkings', [ParkingController::class, 'index']);
    Route::post('/parkings', [ParkingController::class, 'store']);
    Route::get('/parkings/{id}', [ParkingController::class, 'show']);
    Route::put('/parkings/{id}', [ParkingController::class, 'update']);
    Route::delete('/parkings/{id}', [ParkingController::class, 'destroy']);
});












// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/parkings', [ParkingController::class, 'index']);
//     Route::post('/parkings', [ParkingController::class, 'store']);
//     Route::get('/parkings/{id}', [ParkingController::class, 'show']);
//     Route::put('/parkings/{id}', [ParkingController::class, 'update']);
//     Route::delete('/parkings/{id}', [ParkingController::class, 'destroy']);

//     Route::get('/reservations', [ReservationController::class, 'index']);
//     Route::post('/reservations', [ReservationController::class, 'store']);
//     Route::get('/reservations/{id}', [ReservationController::class, 'show']);
//     Route::put('/reservations/{id}', [ReservationController::class, 'update']);
//     Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
// });
