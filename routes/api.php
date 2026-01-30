<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ResumeController;
use App\Http\Controllers\Api\ReviewController;

// Public auth routes (CORS handled by runtime middleware)
Route::middleware([\App\Http\Middleware\CorsMiddleware::class])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes (require auth)
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/resume', [ResumeController::class, 'store']);
        Route::get('/resume', [ResumeController::class, 'show']);
        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store']);
    });
});
