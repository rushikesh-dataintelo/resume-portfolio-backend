<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\CorsMiddleware;
use App\Http\Controllers\Api\ResumeController;
use App\Http\Controllers\Api\ReviewController;

Route::middleware([CorsMiddleware::class])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    // Resume endpoints (authenticated)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/resume', [ResumeController::class, 'store']);
        Route::get('/resume', [ResumeController::class, 'show']);
        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store']);
    });
});
