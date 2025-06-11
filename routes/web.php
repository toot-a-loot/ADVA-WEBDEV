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

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// ...for update delete code testing...in TaskController.php
Route::resource('tasks', TaskController::class);
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
