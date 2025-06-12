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

// Password Reset Routes
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.send_code');
Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify_code');
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


