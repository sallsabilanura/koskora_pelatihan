<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoomRentalController;
use App\Http\Controllers\Api\TenantController;

// Route untuk autentikasi Owner
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route untuk autentikasi Tenant via OTP
Route::post('/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route khusus untuk API (diproteksi dengan middleware auth sanctum)
    Route::apiResource('facilities', FacilityController::class);
    Route::apiResource('properties', PropertyController::class);
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('room-rentals', RoomRentalController::class);
    Route::apiResource('tenants', TenantController::class);
    Route::apiResource('rentals', RentalController::class);
    Route::apiResource('payments', PaymentController::class);

