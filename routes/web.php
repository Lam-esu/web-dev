<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    // Registration
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.attempt');

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
     * ALL USERS (Standard Employees + All Admins)
     */
    Route::get('/dashboard', [AttendanceController::class, 'index'])->name('dashboard');
    Route::post('/record-attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    
    // Attendance Log
    Route::get('/attendance-log', [AttendanceController::class, 'log'])->name('attendance.log');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


    /*
     * ALL ADMINS (Master Admin + Regular Admins like HR/IT)
     * (UI gating handles who sees these buttons, but you can wrap this in a custom middleware later if desired)
     */
    Route::get('/register-employees', [EmployeeController::class, 'registration'])->name('employee.registration');
    Route::post('/register-employees/manual', [EmployeeController::class, 'storeManual'])->name('employee.storeManual');


    /*
     * MASTER ADMIN ONLY
     */
    Route::middleware(['role:admin'])->group(function () {
        
        // User Management & Approvals
        Route::get('/users', [EmployeeController::class, 'users'])->name('users.index');
        Route::post('/users/approve', [EmployeeController::class, 'store'])->name('users.approve');
        Route::post('/users/reject', [EmployeeController::class, 'reject'])->name('users.reject');
        Route::post('/users/update-role', [EmployeeController::class, 'updateRole'])->name('users.updateRole');
        
    });

});