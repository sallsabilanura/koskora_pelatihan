<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminAuthController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\FacilityController;
use App\Http\Controllers\Web\PropertyController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\RoomRentalController;
use App\Http\Controllers\Web\TenantController;
use App\Http\Controllers\Web\RentalController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\SettingController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Web Routes
Route::prefix('admin')->group(function () {
    
    // Guest routes (Login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    // Authenticated admin routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        
        // Web Resource Routes
        Route::name('admin.')->group(function () {
            Route::resource('facilities', FacilityController::class);
            Route::resource('properties', PropertyController::class);
            Route::resource('rooms', RoomController::class);
            Route::resource('room-rentals', RoomRentalController::class);
            Route::resource('tenants', TenantController::class);
            Route::resource('rentals', RentalController::class);
            Route::resource('payments', PaymentController::class);
            
            // Reports Route
            Route::get('reports', [App\Http\Controllers\Web\ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/print', [App\Http\Controllers\Web\ReportController::class, 'print'])->name('reports.print');

            Route::resource('users', App\Http\Controllers\Web\UserController::class);
            Route::post('users/{user}/reset-password', [App\Http\Controllers\Web\UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::post('users/{user}/toggle-status', [App\Http\Controllers\Web\UserController::class, 'toggleStatus'])->name('users.toggle-status');
            
            // Settings Route
            Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
            Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        });
    });

});
