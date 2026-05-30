<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\MemberController;

// ── Guest routes ──
Route::get('/',       [AuthController::class, 'showLogin'])->name('login');
Route::get('/login',  [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// ── Authenticated routes ──
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Book catalogue
    Route::resource('books', BookController::class);

    // Lending / issues
    Route::get('/issues',                      [IssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/overdue',              [IssueController::class, 'overdue'])->name('issues.overdue');
    Route::get('/issues/create',               [IssueController::class, 'create'])->name('issues.create');
    Route::post('/issues',                     [IssueController::class, 'store'])->name('issues.store');
    Route::get('/issues/{issue}',              [IssueController::class, 'show'])->name('issues.show');
    Route::patch('/issues/{issue}/return',     [IssueController::class, 'returnBook'])->name('issues.return');

    // Members registry (uses {member} binding → User model via RouteServiceProvider)
    Route::get('/members',                         [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create',                  [MemberController::class, 'create'])->name('members.create');
    Route::post('/members',                        [MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}',                [MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}/edit',           [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}',                [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}',             [MemberController::class, 'destroy'])->name('members.destroy');
    Route::patch('/members/{member}/toggle-status',[MemberController::class, 'toggleStatus'])->name('members.toggle-status');
});
