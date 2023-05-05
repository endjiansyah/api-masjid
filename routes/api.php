<?php

use App\Http\Controllers\MasjidController;
use App\Http\Controllers\MediaController;
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
Route::get("/masjid", [MasjidController::class, "index"]);
Route::get("/masjid/{id}", [MasjidController::class, "show"]);
Route::post("/masjid", [MasjidController::class, "store"]);
Route::post("/masjid/update/{id}", [MasjidController::class, "update"]);
Route::post("/masjid/delete/{id}", [MasjidController::class, "destroy"]);

Route::get("/media/{id}", [MediaController::class, "show"]);
Route::post("/media", [MediaController::class, "store"]);
Route::post("/media/delete/{id}", [MediaController::class, "Destroymedia"]);