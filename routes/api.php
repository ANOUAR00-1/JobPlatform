<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\CandidatController;

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

    // [JNV-14] - Task: En tant qu'entreprise, je veux voir les candidatures reçues afin de les gérer.
    Route::get('/entreprise/candidatures', [CandidatureController::class, 'indexEntreprise'])
        ->middleware('check.role:entreprise');

    // [JNV-24] - Task: En tant qu'entreprise, je veux accepter une candidature afin de recruter le candidat.
    Route::post('/candidatures/{candidature}/accepter', [CandidatureController::class, 'accepter'])
        ->middleware('check.role:entreprise');

    // [JNV-25] - Task: En tant qu'entreprise, je veux refuser une candidature afin de gérer le recrutement.
    Route::post('/candidatures/{candidature}/refuser', [CandidatureController::class, 'refuser'])
        ->middleware('check.role:entreprise');
});


Route::middleware(['auth:sanctum', 'check.role:candidat'])->group(function () {
    
    // JNV-7 and JNV-8: CV
    
    Route::post('/candidat/profile', [CandidatController::class, 'updateProfile']); 
    
    // JNV-23:  (Candidatures)
    Route::get('/candidat/candidatures', [CandidatController::class, 'indexCandidatures']);

});