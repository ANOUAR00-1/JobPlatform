<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\CandidatureController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register/entreprise', [AuthController::class, 'registerEntreprise']);
    Route::post('/register/candidat', [AuthController::class, 'registerCandidat']);
});

// Protected routes (require Sanctum authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Get authenticated user info
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('entreprise');
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    });

    // Job Offers - Entreprise only
    Route::prefix('offres')->middleware('check.role:entreprise')->group(function () {
        Route::post('/', [OffreController::class, 'store']); // JNV-15: Create job offer
    });

    // Job Applications - Candidat only
    Route::prefix('candidatures')->middleware('check.role:candidat')->group(function () {
        Route::post('/', [CandidatureController::class, 'store']); // JNV-22: Apply for job
    });
});
