<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    Route::get('/approval', [AuthController::class, 'unApprove'])->name('approval');
    Route::post('/check-token', [UserController::class, 'checkToken'])->name('check.token');
    Route::post('/login-principle', [UserController::class, 'loginPrinciple'])->name('login.principle');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/absen', [DashboardController::class, 'absen'])->name('absen');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/chat', [DashboardController::class, 'chat'])->name('chat');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('update.profile');
    Route::post('/absen', [AbsenController::class, 'createAbsen'])->name('create.abses');
    Route::get('/chat-room', [ChatController::class, 'getRoom'])->name('get.room');
    Route::post('/chat-room/send', [ChatController::class, 'sendMessage'])->name('send.message');
    Route::get('/list-approval', [UserController::class, 'approveUser'])->name('approve.user');
    Route::post('/list-approval', [UserController::class, 'handleApprove'])->name('approve.user');
    Route::get('/list-token', [DashboardController::class, 'token'])->name('token.list');
    Route::post('/generate-token', [UserController::class, 'generateToken'])->name('generate.token');
    Route::post('/share-token', [UserController::class, 'shareToken'])->name('share.token');
});
