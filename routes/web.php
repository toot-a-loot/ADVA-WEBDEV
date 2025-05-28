<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// Show login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Handle login POST
Route::post('/login', [AuthController::class, 'login'])->name('public/login');

// Show register form
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Handle register POST
Route::post('/register', [AuthController::class, 'register']);

// Forgot password (if using Laravel's built-in)
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

require __DIR__.'/auth.php';
