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

// Task routes
Route::middleware(['auth'])->group(function () {
    // Get user's tasks
    Route::get('/tasks/user', [TaskController::class, 'getUserTasks'])->name('tasks.user');

    // Task management routes
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/save', [TaskController::class, 'save'])->name('tasks.save');
});

Route::get('/board', function () {
    return view('board');
});

Route::get('/profile/edit', function () {
    return view('profile-edit');
})->name('profile.edit');

// add task for desktop
Route::middleware(['auth'])->group(function () {
    Route::get('/task', function () {
        return view('edit-task');
    });

    // para ni for spawning components
    Route::get('/spawn/{type}', function ($type) {
        if (in_array($type, ['task', 'column', 'image'])) {
            return view('components.' . $type);
        }
        abort(404);
    });

    Route::post('/tasks/save', [TaskController::class, 'save'])->name('tasks.save');
    Route::get('/tasks/user', [TaskController::class, 'getUserTasks'])->name('tasks.user');
});

// All task-related routes should be in the auth middleware group
