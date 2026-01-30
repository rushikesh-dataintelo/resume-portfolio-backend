<?php

use Illuminate\Support\Facades\Route;

// Health check - safe endpoint that does not depend on DB
Route::get('/health', function () {
    return response('OK', 200);
});

// Secure DB health endpoint (requires DB_HEALTH_SECRET query param or X-Health-Secret header)
Route::get('/health/db', function (\Illuminate\Http\Request $request) {
    $secret = $request->query('secret') ?? $request->header('X-Health-Secret');

    if (! $secret || $secret !== env('DB_HEALTH_SECRET')) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    try {
        // Try a simple DB interaction
        \Illuminate\Support\Facades\DB::connection()->getPdo();

        return response()->json(['status' => 'ok', 'database' => true], 200);
    } catch (\Throwable $e) {
        // Log error, but don't leak details
        report($e);

        return response()->json(['status' => 'error', 'database' => false], 500);
    }
});

Route::get('/', function () {
    return view('welcome');
});
