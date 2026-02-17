<?php

use App\Http\Controllers\Api\AstrologerController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/astrologers', [AstrologerController::class, 'index']);
Route::get('/astrologers/{id}', [AstrologerController::class, 'show']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'userData']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});