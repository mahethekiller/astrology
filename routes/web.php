<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\BlogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::get('/blog', [App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\Frontend\BlogController::class, 'show'])->name('blog.show');















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

// ADMIN Routes - Only for users with admin role
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // Admin Components
    Route::get('/components', [ComponentsController::class, 'adminIndex'])->name('components');

    // Admin Tables
    Route::get('/tables', [TablesController::class, 'adminIndex'])->name('tables');


    // sliders
    Route::resource('sliders', SliderController::class);
    Route::patch('sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');

    // Newsletters
    Route::resource('newsletters', AdminNewsletterController::class)->only(['index', 'destroy']);

    // Testimonials
    Route::resource('testimonials', TestimonialController::class);

    // Blogs
    Route::resource('blogs', BlogController::class);




    // Role Management
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Permission Management
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
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
