<?php

use App\Http\Controllers\Web\AdminAuthController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\FacilityController;
use App\Http\Controllers\Web\PasswordChangeController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\PropertyController;
use App\Http\Controllers\Web\RentalController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\RoomRentalController;
use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Middleware\ForcePasswordChange;
use Illuminate\Support\Facades\Route;

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

        // Force Password Change Routes
        Route::get('/password/change', [PasswordChangeController::class, 'showChangeForm'])->name('password.change');
        Route::post('/password/change', [PasswordChangeController::class, 'changePassword'])->name('password.update');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::middleware([ForcePasswordChange::class])->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
            Route::get('/notifications', [AdminDashboardController::class, 'notifications'])->name('admin.notifications.index');

            // Web Resource Routes
            Route::name('admin.')->group(function () {
                Route::resource('facilities', FacilityController::class);
                Route::resource('properties', PropertyController::class);
                Route::resource('rooms', RoomController::class);
                Route::resource('room-rentals', RoomRentalController::class);
                Route::resource('rentals', RentalController::class);
                Route::post('rentals/{rental}/toggle-status', [RentalController::class, 'toggleStatus'])->name('rentals.toggle-status');
                Route::resource('payments', PaymentController::class);

                // Reports Route
                Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
                Route::get('reports/print', [ReportController::class, 'print'])->name('reports.print');

                Route::resource('users', UserController::class);
                Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
                Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

                // Settings Route
                Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
                Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

                // Profile Routes
                Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
                Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
                Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
            });

        }); // End ForcePasswordChange middleware
    });

});
