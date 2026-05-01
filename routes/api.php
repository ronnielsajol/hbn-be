<?php

use App\Http\Controllers\GreetingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Greeting endpoints (no auth required)
Route::post('/greetings', [GreetingController::class, 'store']);
Route::get('/greetings', [GreetingController::class, 'index']);
Route::get('/greetings/{id}', [GreetingController::class, 'show']);
