<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OffreController;
<<<<<<< HEAD
use App\Http\Controllers\CandidatureController;

/*
|--------------------------------------------------------------------------
| API Routes - JobNow Minimalist API
|--------------------------------------------------------------------------
| JNV-2:  POST /api/auth/register/entreprise
| JNV-15: POST /api/offres
| JNV-22: POST /api/candidatures
*/

// [JNV-2] Public: Entreprise Registration
Route::post('/auth/register/entreprise', [AuthController::class, 'registerEntreprise']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // [JNV-15] Entreprise: Post Job Offer
    Route::post('/offres', [OffreController::class, 'store'])
        ->middleware('check.role:entreprise');

    // [JNV-22] Candidat: Apply for Job
    Route::post('/candidatures', [CandidatureController::class, 'store'])
        ->middleware('check.role:candidat');
=======

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/jobs', [OffreController::class, 'index']);
  
>>>>>>> origin/feature/dev
});
