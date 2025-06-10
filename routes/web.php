<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

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

// REMOVE THIS LINE - we don't need it for same-page functionality
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Handle password reset request (keep this)
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Handle password reset form (for when user clicks email link)
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
