<?php
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect the root URL to the login page.
Route::get('/', function () {
    return redirect()->route('login');
});

// ----------------------------------------------------------------------
// Guest routes — only accessible when NOT logged in.
// ----------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.attempt');
});

// ----------------------------------------------------------------------
// Authenticated routes — only accessible when logged in.
// ----------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    
    // Shared Routes (Both Students and Admins can see the dashboard and logout)
    Route::get('/dashboard', [AttendanceController::class, 'index'])->name('dashboard');
    Route::post('/record-attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin-Only Routes (Protected by the new 'role:admin' middleware)
    Route::middleware(['role:admin'])->group(function () {
        // Employee/Student Registration
        Route::get('/employee-registration', [EmployeeController::class, 'create'])->name('employee.registration');
        Route::post('/employee-registration', [EmployeeController::class, 'store'])->name('employee.store');

        // Full Attendance Log
        Route::get('/attendance-log', [AttendanceController::class, 'log'])->name('attendance.log');
    });

});