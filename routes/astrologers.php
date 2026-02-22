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

    // My Earnings
    Route::get('/revenue', [App\Http\Controllers\Astrologer\RevenueController::class, 'index'])->name('revenue.index');

    // Profile Management
    Route::get('/profile/edit', [App\Http\Controllers\Astrologer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [App\Http\Controllers\Astrologer\ProfileController::class, 'update'])->name('profile.update');

    // Wallet System
    Route::get('/wallet', [App\Http\Controllers\Astrologer\WalletController::class, 'index'])->name('wallet.index');
});
