<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('login');
});

// Show login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Handle login POST
Route::post('/login', [AuthController::class, 'login']);

// Show register form
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Handle register POST
Route::post('/register', [AuthController::class, 'register']);

// Handle password reset request (keep this)
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Show forgot password form (if needed)
Route::get('/forgot-password', function () {
    return view('auth.forgot_password');
})->name('forgot.password');

// Send code to email
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email');

// Show code entry form
Route::get('/enter-code', [ForgotPasswordController::class, 'showCodeForm'])->name('password.code.form');

// Verify code and reset password
Route::post('/verify-reset-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify_code');

// Reset password
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

//Calendar
Route::get('/calendar', function () {
    return view('calendar'); // loads resources/views/about.blade.php
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/board', function () {
    return view('board');
});

Route::get('/profile/edit', function () {
    // Return a view or controller for editing the profile
    return view('profile-edit');
})->name('profile.edit');
