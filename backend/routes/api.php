<?php

use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\ClanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('players')->group(function () {
    Route::get('/', [PlayerController::class, 'index']);
    Route::get('/create-dummy', [PlayerController::class, 'createDummy']);
    Route::get('/fetch-from-api/{tag}', [PlayerController::class, 'fetchFromApi']);
    Route::get('/{tag}', [PlayerController::class, 'show']);
});

Route::prefix('clans')->group(function () {
    Route::get('/', [ClanController::class, 'index']);
    Route::get('/fetch-from-api/{tag}', [ClanController::class, 'fetchFromApi']);
    Route::get('/{tag}', [ClanController::class, 'show']);
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

    // Clan management (protected operations)
    Route::prefix('clans')->group(function () {
        Route::post('/', [ClanController::class, 'store']);
        Route::put('/{tag}', [ClanController::class, 'update']);
        Route::delete('/{tag}', [ClanController::class, 'destroy']);
    });
});
