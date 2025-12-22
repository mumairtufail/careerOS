<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\AiConfigurationController;
use App\Http\Controllers\JobStageController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\LogController;
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

    // Job Applications Routes
    Route::resource('job-applications', JobApplicationController::class);

    // Job Stages Routes
    Route::resource('job-stages', JobStageController::class)->except(['show']);

    // Resume Routes
    Route::resource('resumes', ResumeController::class);
    Route::post('/resumes/bulk-destroy', [ResumeController::class, 'bulkDestroy'])->name('resumes.bulk-destroy');
    Route::post('/resumes/{resume}/re-parse', [ResumeController::class, 'reParse'])->name('resumes.re-parse');

    // AI Configuration Routes
    Route::resource('ai-configurations', AiConfigurationController::class);
    Route::post('/ai-configurations/{aiConfiguration}/activate', [AiConfigurationController::class, 'activate'])->name('ai-configurations.activate');
    Route::post('/ai-configurations/fetch-models', [AiConfigurationController::class, 'fetchModels'])->name('ai-configurations.fetch-models');

    // System Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
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
