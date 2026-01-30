<?php

use Illuminate\Support\Facades\Route;

// Health check - safe endpoint that does not depend on DB
Route::get('/health', function () {
    return response('OK', 200);
});

Route::get('/', function () {
    return view('welcome');
});
