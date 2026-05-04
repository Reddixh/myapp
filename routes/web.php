<?php

Route::get('/debug-auth', function() {
    if (auth()->check()) {
        return response()->json([
            'logged_in' => true,
            'name'      => auth()->user()->name,
            'role'      => auth()->user()->role,
            'email'     => auth()->user()->email,
        ]);
    }
    return response()->json(['logged_in' => false]);
});

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Staff\StaffAuthController;
use App\Http\Controllers\Staff\LibrarianController;
use App\Http\Controllers\Staff\DeanController;
use App\Http\Controllers\Staff\ITController;

// ─── Student Auth ─────────────────────────────
Route::get('/', function() { return view('welcome'); })->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/clearance/download', [StudentController::class, 'downloadClearance'])->name('clearance.download');
Route::get('/financial-statement', [StudentController::class, 'financialStatement'])->name('financial.statement');
Route::post('/notifications/read-all', [StudentController::class, 'markNotifsRead'])->name('notifs.read');
Route::get('/notifications', [StudentController::class, 'allNotifications'])->name('notifs.all');

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
Route::get('/staff/books', [LibrarianController::class, 'allBooks'])->name('staff.books');
Route::post('/staff/books/{userId}', [LibrarianController::class, 'addBook'])->name('staff.book.add');
Route::post('/staff/books/return/{bookId}', [LibrarianController::class, 'returnBook'])->name('staff.book.return');

// ─── Librarian Panel ───────────────────────────
Route::middleware(['auth', 'staff:librarian'])->group(function () {
    Route::get('/staff/dashboard', [LibrarianController::class, 'dashboard'])->name('staff.dashboard');
    Route::get('/staff/student/{userId}', [LibrarianController::class, 'showStudent'])->name('staff.student');
    Route::post('/staff/approve/{userId}', [LibrarianController::class, 'approve'])->name('staff.approve');
    Route::post('/staff/reject/{userId}', [LibrarianController::class, 'reject'])->name('staff.reject');
    Route::post('/staff/penalty/{userId}', [LibrarianController::class, 'addPenalty'])->name('staff.penalty');
    Route::post('/staff/control-number/{penaltyId}', [LibrarianController::class, 'issueControlNumber'])->name('staff.control');
    Route::get('/staff/books', [LibrarianController::class, 'allBooks'])->name('staff.books');
    Route::post('/staff/books/{userId}', [LibrarianController::class, 'addBook'])->name('staff.book.add');
    Route::post('/staff/books/return/{bookId}', [LibrarianController::class, 'returnBook'])->name('staff.book.return');
}); // ← Make sure this closing bracket is here!

// ─── Dean Panel ────────────────────────────────
Route::middleware(['auth', 'staff:dean'])->group(function () {
    Route::get('/dean/dashboard', [DeanController::class, 'dashboard'])->name('dean.dashboard');
    Route::get('/dean/student/{userId}', [DeanController::class, 'showStudent'])->name('dean.student');
    Route::post('/dean/approve/{userId}', [DeanController::class, 'approve'])->name('dean.approve');
    Route::post('/dean/reject/{userId}', [DeanController::class, 'reject'])->name('dean.reject');
    Route::post('/dean/penalty/{userId}', [DeanController::class, 'addPenalty'])->name('dean.penalty');
    Route::post('/dean/conduct/{userId}', [DeanController::class, 'addConductRecord'])->name('dean.conduct');
    Route::post('/dean/conduct/resolve/{recordId}', [DeanController::class, 'resolveRecord'])->name('dean.resolve');
    Route::get('/dean/records', [DeanController::class, 'allRecords'])->name('dean.records');
});

// ─── IT Panel ──────────────────────────────────
Route::middleware(['auth', 'staff:it'])->group(function () {
    Route::get('/it/dashboard', [ITController::class, 'dashboard'])->name('it.dashboard');
    Route::get('/it/student/{userId}', [ITController::class, 'showStudent'])->name('it.student');
    Route::post('/it/approve/{userId}', [ITController::class, 'approve'])->name('it.approve');
    Route::post('/it/reject/{userId}', [ITController::class, 'reject'])->name('it.reject');
    Route::post('/it/penalty/{userId}', [ITController::class, 'addPenalty'])->name('it.penalty');
    Route::post('/it/equipment/{userId}', [ITController::class, 'addEquipment'])->name('it.equipment.add');
    Route::post('/it/equipment/return/{equipmentId}', [ITController::class, 'returnEquipment'])->name('it.equipment.return');
    Route::post('/it/equipment/lost/{equipmentId}', [ITController::class, 'markLost'])->name('it.equipment.lost');
    Route::get('/it/equipment', [ITController::class, 'allEquipment'])->name('it.equipment');
});