<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Staff\StaffAuthController;
use App\Http\Controllers\Staff\LibrarianController;

// ─── Student Auth ─────────────────────────────
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Student Pages ─────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/clearance', [StudentController::class, 'clearance'])->name('clearance');
    Route::post('/clearance/request/{department}', [StudentController::class, 'requestClearance'])->name('clearance.request');
    Route::get('/control-numbers', [StudentController::class, 'controlNumbers'])->name('control.numbers');
    Route::get('/request-control-number', [StudentController::class, 'requestControlNumber'])->name('request.control');
    Route::post('/control-numbers/request', [StudentController::class, 'submitControlNumber'])->name('control.request');
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
});

// ─── Staff Auth ────────────────────────────────
Route::get('/staff/login', [StaffAuthController::class, 'showLogin'])->name('staff.login');
Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.submit');
Route::get('/staff/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');

// ─── Librarian Panel ───────────────────────────
Route::middleware(['auth', 'staff:librarian'])->group(function () {
    Route::get('/staff/dashboard', [LibrarianController::class, 'dashboard'])->name('staff.dashboard');
    Route::get('/staff/student/{userId}', [LibrarianController::class, 'showStudent'])->name('staff.student');
    Route::post('/staff/approve/{userId}', [LibrarianController::class, 'approve'])->name('staff.approve');
    Route::post('/staff/reject/{userId}', [LibrarianController::class, 'reject'])->name('staff.reject');
    Route::post('/staff/penalty/{userId}', [LibrarianController::class, 'addPenalty'])->name('staff.penalty');
    Route::post('/staff/control-number/{penaltyId}', [LibrarianController::class, 'issueControlNumber'])->name('staff.control');
});