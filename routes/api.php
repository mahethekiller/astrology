<?php

use App\Http\Controllers\Api\AstrologerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\ZodiacSignController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\TwilioTokenController;
use App\Http\Controllers\Api\TwilioWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/astrologers', [AstrologerController::class, 'index']);
Route::get('/astrologers/{id}', [AstrologerController::class, 'show']);
Route::get('/sliders', [SliderController::class, 'index']);
Route::get('/search', [SearchController::class, 'index']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);
Route::get('/zodiac-signs', [ZodiacSignController::class, 'index']);
Route::get('/statistics', [StatisticsController::class, 'index']);
Route::get('/ratings', [RatingController::class, 'index']);

// Twilio Webhooks
Route::post('/twilio/voice-webhook', [TwilioWebhookController::class, 'voiceWebhook']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'userData']);
    Route::get('/wallet/balance', [WalletController::class, 'balance']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
    Route::post('/wallet/add', [WalletController::class, 'addFunds']);
    Route::post('/wallet/deduct', [WalletController::class, 'deductFunds']);

    // Consultation Completions & Requests
    Route::post('/consultations/request-chat', [ConsultationController::class, 'requestChat']);
    Route::post('/consultations/request-call', [ConsultationController::class, 'requestCall']);
    Route::get('/consultations/chat-status/{id}', [ConsultationController::class, 'checkChatStatus']);
    Route::get('/consultations/call-status/{id}', [ConsultationController::class, 'checkCallStatus']);
    Route::post('/consultations/end-chat', [ConsultationController::class, 'endChat']);
    Route::post('/consultations/end-call', [ConsultationController::class, 'endCall']);

    // Ratings
    Route::post('/ratings', [RatingController::class, 'store']);

    // Twilio Tokens
    Route::get('/twilio/chat-token', [TwilioTokenController::class, 'chatToken']);
    Route::get('/twilio/voice-token', [TwilioTokenController::class, 'voiceToken']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});