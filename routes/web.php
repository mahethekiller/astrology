<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome')->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::get('/blog', [App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\Frontend\BlogController::class, 'show'])->name('blog.show');

Route::get('/astrologers', [App\Http\Controllers\Frontend\AstrologerController::class, 'index'])->name('astrologer.index');
Route::get('/astrologer/{id}', [App\Http\Controllers\Frontend\AstrologerController::class, 'show'])->name('astrologer.show')->where('id', '[0-9]+');


Route::get('/horoscope/daily/{sign?}', [App\Http\Controllers\Frontend\HoroscopeController::class, 'daily'])->name('horoscope.daily');
Route::get('/kundli', [App\Http\Controllers\Frontend\KundliController::class, 'index'])->name('kundli.index');
Route::post('/kundli', [App\Http\Controllers\Frontend\KundliController::class, 'generate'])->name('kundli.generate');
Route::get('/kundli/detailed', [App\Http\Controllers\Frontend\KundliController::class, 'show'])->name('kundli.detailed');



// Theme toggle - accessible to all authenticated users
Route::middleware('auth')->group(function () {
    Route::post('/theme/toggle', [DashboardController::class, 'toggleTheme'])->name('theme.toggle');
});

// Profile routes - accessible to all authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// MANAGER Routes - Only for users with manager role
Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    // Manager Dashboard
    Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('dashboard');

    // Manager Components
    Route::get('/components', [ComponentsController::class, 'managerIndex'])->name('components');

    // Manager Tables
    Route::get('/tables', [TablesController::class, 'managerIndex'])->name('tables');

    // Manager specific routes can be added here
    Route::get('/reports', function () {
        return view('manager.reports');
    })->name('reports');
});

// USER Routes - For regular users
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');

    // User Profile (override the general profile if needed)
    Route::get('/profile', [ProfileController::class, 'userEdit'])->name('profile.edit');


    Route::patch('/profile', [ProfileController::class, 'userUpdate'])->name('profile.update');


    // User specific routes
    Route::get('/settings', function () {
        return view('user.settings');
    })->name('settings');
});

// General routes that don't require specific role prefixes
Route::middleware(['auth'])->group(function () {
    // These remain accessible to all authenticated users without role prefix
    Route::get('/components', [ComponentsController::class, 'index'])->name('components');
    Route::get('/tables', [TablesController::class, 'index'])->name('tables');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/astrologers.php';

// OTP Auth Routes
Route::post('/login/send-otp', [App\Http\Controllers\Auth\OtpAuthController::class, 'sendOtp'])->name('login.send-otp');
Route::post('/login/verify-otp', [App\Http\Controllers\Auth\OtpAuthController::class, 'verifyOtp'])->name('login.verify-otp');
Route::post('/login/register-otp', [App\Http\Controllers\Auth\OtpAuthController::class, 'registerWithOtp'])->name('login.register-otp');

// Wallet Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/wallet', [App\Http\Controllers\Frontend\WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/add', [App\Http\Controllers\Frontend\WalletController::class, 'addMoney'])->name('wallet.add');

    // Chat Routes
    // Chat Routes
    Route::get('/chat/request/{astrologerId}', [App\Http\Controllers\Frontend\ChatController::class, 'requestChat'])->name('chat.initiate'); // Keeping name for backward compat
    Route::get('/chat/waiting/{requestId}', [App\Http\Controllers\Frontend\ChatController::class, 'waiting'])->name('chat.waiting');
    Route::get('/chat/request/{requestId}/status', [App\Http\Controllers\Frontend\ChatController::class, 'checkStatus'])->name('chat.request.status');
    Route::post('/chat/session/status', [App\Http\Controllers\Frontend\ChatController::class, 'checkSessionStatus'])->name('chat.session.status');
    Route::get('/chat/history', [App\Http\Controllers\Frontend\ChatController::class, 'history'])->name('chat.history');

    Route::get('/chat/room/{sid}', [App\Http\Controllers\Frontend\ChatController::class, 'room'])->name('chat.room');
    Route::post('/chat/token', [App\Http\Controllers\Frontend\ChatController::class, 'token'])->name('chat.token');
    Route::post('/chat/end', [App\Http\Controllers\Frontend\ChatController::class, 'endChat'])->name('chat.end');
    Route::post('/chat/billing/ping', [App\Http\Controllers\Frontend\ChatController::class, 'billingPing'])->name('chat.billing.ping');

    // Call Routes - Token must be authenticated
    Route::post('/call/token', [App\Http\Controllers\Frontend\CallController::class, 'token'])->name('call.token');

    // Call Views
    Route::get('/call/astrologer/{astrologerId}', function ($astrologerId) {
        $astrologer = \App\Models\AstrologerProfile::findOrFail($astrologerId);
        return view('frontend.call.index', compact('astrologer'));
    })->name('call.initiate');

    Route::get('/astrologer/call-dashboard', function () {
        return view('astrologer.call.dashboard');
    })->middleware(['auth', 'role:astrologer'])->name('astrologer.call.dashboard');

    // Astrologer History
    Route::get('/astrologer/history', [App\Http\Controllers\Astrologer\HistoryController::class, 'index'])
        ->middleware(['auth', 'role:astrologer'])
        ->name('astrologer.history.index');
});

// Twilio Webhooks - Must be PUBLIC (No Auth Middleware)
Route::post('/call/connect', [App\Http\Controllers\Frontend\CallController::class, 'voiceCallback'])->name('call.connect');
Route::post('/call/status', [App\Http\Controllers\Frontend\CallController::class, 'callStatusCallback'])->name('call.status');

// API Routes
Route::get('/api/location/search', [App\Http\Controllers\Api\LocationController::class, 'search'])->name('api.location.search');

