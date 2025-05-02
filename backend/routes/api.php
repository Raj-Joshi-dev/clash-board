<?php

use App\Http\Controllers\Api\PlayerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('players')->group(function () {
    Route::get('/', [PlayerController::class, 'index']);
    Route::get('/{tag}', [PlayerController::class, 'show']);
    Route::get('/create-dummy', [PlayerController::class, 'createDummy']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Player management (protected operations)
    Route::prefix('players')->group(function () {
        Route::post('/', [PlayerController::class, 'store']);
        Route::put('/{tag}', [PlayerController::class, 'update']);
        Route::delete('/{tag}', [PlayerController::class, 'destroy']);
    });
});
