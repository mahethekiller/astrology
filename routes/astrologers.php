<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Astrologer\ChatController;

Route::get('astrologer/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('astrologer.login');

Route::middleware(['auth'])->prefix('astrologer')->name('astrologer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'astrologerDashboard'])->name('dashboard');
    Route::get('/chat/requests', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/accept/{id}', [ChatController::class, 'accept'])->name('chat.accept');
    Route::get('/chat/reject/{id}', [ChatController::class, 'reject'])->name('chat.reject');
    Route::get('/chat/room/{sid}', [ChatController::class, 'room'])->name('chat.room');
    Route::post('/status/toggle', [ChatController::class, 'toggleStatus'])->name('status.toggle');
    Route::get('/requests/pending', [DashboardController::class, 'getPendingRequests'])->name('requests.pending');
    Route::get('/chat/history', [ChatController::class, 'history'])->name('chat.history');
});
