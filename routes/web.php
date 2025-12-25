<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::get('/blog', [App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\Frontend\BlogController::class, 'show'])->name('blog.show');

Route::get('/astrologers', [App\Http\Controllers\Frontend\AstrologerController::class, 'index'])->name('astrologer.index');
Route::get('/astrologer/{id}', [App\Http\Controllers\Frontend\AstrologerController::class, 'show'])->name('astrologer.show');




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
