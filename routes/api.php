<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\CandidatureController;

/*
|--------------------------------------------------------------------------
| API Routes - JobNow API
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/register/entreprise', [AuthController::class, 'registerEntreprise']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // List jobs (from feature/dev)
    Route::get('/jobs', [OffreController::class, 'index']);

    // [JNV-15] Create job offer (entreprise only)
    Route::post('/offres', [OffreController::class, 'store'])
        ->middleware('check.role:entreprise');

    // [JNV-22] Apply for job (candidat only)
    Route::post('/candidatures', [CandidatureController::class, 'store'])
        ->middleware('check.role:candidat');
});
