<?php
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TablesController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SliderController;



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
    Route::resource('newsletters', NewsletterController::class)->only(['index', 'destroy']);

    // Testimonials
    Route::resource('testimonials', TestimonialController::class);

    // Blogs
    Route::resource('blogs', BlogController::class);

    // User Management
    Route::resource('users', UserController::class);

    // Astrologer Profile Management
    Route::resource('astrologer-profiles', App\Http\Controllers\Admin\AstrologerProfileController::class);

    // Specializations Management
    Route::resource('admin/specializations', App\Http\Controllers\Admin\SpecializationController::class);

    // Languages Management
    Route::resource('admin/languages', App\Http\Controllers\Admin\LanguageController::class);



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