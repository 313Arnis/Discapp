<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);

Route::get('/competitions', [CompetitionController::class, 'index']);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/competitions/create', [CompetitionController::class, 'create']);
    Route::post('/competitions', [CompetitionController::class, 'store']);

    Route::get('/competitions/{competition}/edit', [CompetitionController::class, 'edit']);
    Route::put('/competitions/{competition}', [CompetitionController::class, 'update']);

    Route::delete('/competitions/{competition}', [CompetitionController::class, 'destroy']);
});

Route::get('/competitions/{competition}', [CompetitionController::class, 'show']);
});