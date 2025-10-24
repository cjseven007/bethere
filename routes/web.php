<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EmployeesController;
use Illuminate\Support\Facades\Route;



Route::get('/', fn() => redirect()->route('attendance.scan'));

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/users', [UserController::class,'index'])->name('users.index');
    Route::get('/attendance/scan', [ScanController::class, 'show'])->name('attendance.scan');
    Route::post('/attendance/identify', [ScanController::class, 'identify'])->name('attendance.identify');

    Route::get('/settings', [SettingsController::class,'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class,'update'])->name('settings.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UsersController::class,'index'])->name('users.index');

    // Embedding capture endpoint (image upload)
    Route::post('/employees/{employee}/embed', [EmployeesController::class, 'store'])
    ->name('employees.embed');
});



require __DIR__.'/auth.php';
