<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users Management Routes
    Route::resource('users', UserController::class)->except(['show']);
});

// Under Construction Route (for pages not yet implemented)
Route::get('/under-construction', function () {
    return view('errors.under-construction');
})->name('under-construction');

// Fallback Route for undefined pages
Route::fallback(function () {
    return view('errors.under-construction');
});

require __DIR__.'/auth.php';
