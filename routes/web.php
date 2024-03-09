<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(["guest"])->group(function () {
    Route::get('/auth/login', [AuthController::class, 'login'])->name('login');
    Route::post('/auth/login', [AuthController::class, 'isLogin']);
    Route::get('/auth/register', [AuthController::class, 'register'])->name('register');
    Route::post('/auth/register', [AuthController::class, 'createUser']);
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'handleResetPassword'])->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/email/verify', [AuthController::class, 'unVerified'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'isVerified'])->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendLink'])->middleware(['throttle:6,1'])->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'home']);
});
