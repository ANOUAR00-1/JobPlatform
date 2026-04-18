<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OAuthController;

/*
|--------------------------------------------------------------------------
| API Routes - JobNow API
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/auth/register/entreprise', [AuthController::class, 'registerEntreprise']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// OAuth routes
Route::get('/auth/google', [OAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [OAuthController::class, 'handleGoogleCallback']);

// Public job routes
Route::get('/jobs', [OffreController::class, 'index']);
Route::get('/jobs/{id}', [OffreController::class, 'show']);

// AI Public Route
Route::post('/chat', [ChatbotController::class, 'ask']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Get authenticated user data
    Route::get('/user', [AuthController::class, 'getUser']);

    // [JNV-15] Create job offer (entreprise only)
    Route::post('/offres', [OffreController::class, 'store'])
        ->middleware('check.role:entreprise');

    // [JNV-22] Apply for job (candidat only)
    Route::post('/candidatures', [CandidatureController::class, 'store'])
        ->middleware('check.role:candidat');

    // [JNV-14] - Task: En tant qu'entreprise, je veux voir les candidatures reçues afin de les gérer.
    Route::get('/entreprise/candidatures', [CandidatureController::class, 'indexEntreprise'])
        ->middleware('check.role:entreprise');

    // Enterprise Offers List
    Route::get('/entreprise/offres', [OffreController::class, 'indexEntreprise'])
        ->middleware('check.role:entreprise');

    // [JNV-24] - Task: En tant qu'entreprise, je veux accepter une candidature afin de recruter le candidat.
    Route::post('/candidatures/{candidature}/accepter', [CandidatureController::class, 'accepter'])
        ->middleware('check.role:entreprise');

    // [JNV-25] - Task: En tant qu'entreprise, je veux refuser une candidature afin de gérer le recrutement.
    Route::post('/candidatures/{candidature}/refuser', [CandidatureController::class, 'refuser'])
        ->middleware('check.role:entreprise');
    // [JNV-30] - Task: Evaluate a candidate
    Route::put('/candidatures/{candidature}/evaluate', [CandidatureController::class, 'evaluate'])
        ->middleware('check.role:entreprise');

    // [JNV-27] - Task: Send an interview convocation
    Route::post('/candidatures/{candidature}/convoquer', [CandidatureController::class, 'convoquer'])
        ->middleware('check.role:entreprise');

    // [JNV-33] - Notifications
    Route::get('/entreprise/notifications', [NotificationController::class, 'index'])
        ->middleware('check.role:entreprise');
    Route::post('/entreprise/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->middleware('check.role:entreprise');
});


Route::middleware(['auth:sanctum', 'check.role:candidat'])->group(function () {
    
    // JNV-7 and JNV-8: CV
    
    Route::post('/candidat/profile', [CandidatController::class, 'updateProfile']); 
    
    // JNV-23:  (Candidatures)
    Route::get('/candidat/candidatures', [CandidatController::class, 'indexCandidatures']);

});