<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiscController;
use App\Http\Controllers\ProfileController;


Route::get('/', [HomeController::class, 'index']);

// Auth
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);


// Sacensības
Route::get('/competitions', [CompetitionController::class, 'index']);

Route::middleware('auth')->group(function () {

    Route::get('/competitions/create', [CompetitionController::class, 'create']);
    Route::post('/competitions', [CompetitionController::class, 'store']);

    Route::get('/competitions/{competition}/edit', [CompetitionController::class, 'edit']);
    Route::put('/competitions/{competition}', [CompetitionController::class, 'update']);

    Route::delete('/competitions/{competition}', [CompetitionController::class, 'destroy']);


    // Profils
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

    // Mani diski
    Route::get('/profile/discs', [DiscController::class, 'index'])
        ->name('profile.discs.index');

    Route::get('/profile/discs/create', [DiscController::class, 'create'])
        ->name('profile.discs.create');

    Route::post('/profile/discs', [DiscController::class, 'store'])
        ->name('profile.discs.store');

    Route::delete('/profile/discs/{disc}', [DiscController::class, 'destroy'])
        ->name('profile.discs.destroy');
});


// Vienas sacensības apskate
Route::get('/competitions/{competition}', [CompetitionController::class, 'show']);


// Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
});