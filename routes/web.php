<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiscController;
use App\Http\Controllers\ProfileController;


// =========================
// SĀKUMLAPA
// =========================

Route::get('/', [HomeController::class, 'index'])
    ->name('home');


// =========================
// AUTORIZĀCIJA
// =========================

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


// =========================
// SACENSĪBAS
// =========================

// Sacensību saraksts
Route::get('/competitions', [CompetitionController::class, 'index'])
    ->name('competitions.index');


// =========================
// IESLOGOTI LIETOTĀJI
// =========================

Route::middleware('auth')->group(function () {

    // -------------------------
    // SACENSĪBU IZVEIDE
    // -------------------------

    Route::get('/competitions/create', [CompetitionController::class, 'create'])
        ->name('competitions.create');

    Route::post('/competitions', [CompetitionController::class, 'store'])
        ->name('competitions.store');


    // -------------------------
    // PIEVIENOŠANĀS SACENSĪBĀM
    // -------------------------

    Route::get('/competitions/{competition}/join', [CompetitionController::class, 'join'])
        ->name('competitions.join');

    Route::post('/competitions/{competition}/join', [CompetitionController::class, 'storeJoin'])
        ->name('competitions.join.store');

    Route::post('/competitions/{competition}/leave', [CompetitionController::class, 'leave'])
        ->name('competitions.leave');


    // -------------------------
    // SACENSĪBU REDIĢĒŠANA
    // -------------------------

    Route::get('/competitions/{competition}/edit', [CompetitionController::class, 'edit'])
        ->name('competitions.edit');

    Route::put('/competitions/{competition}', [CompetitionController::class, 'update'])
        ->name('competitions.update');


    // -------------------------
    // SACENSĪBU DZĒŠANA
    // -------------------------

    Route::delete('/competitions/{competition}', [CompetitionController::class, 'destroy'])
        ->name('competitions.destroy');


    // =========================
    // PROFILS
    // =========================

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');


    // =========================
    // MANI DISKI
    // =========================

    Route::get('/profile/discs', [DiscController::class, 'index'])
        ->name('profile.discs.index');

    Route::get('/profile/discs/create', [DiscController::class, 'create'])
        ->name('profile.discs.create');

    Route::post('/profile/discs', [DiscController::class, 'store'])
        ->name('profile.discs.store');

    Route::delete('/profile/discs/{disc}', [DiscController::class, 'destroy'])
        ->name('profile.discs.destroy');
});


// =========================
// VIENAS SACENSĪBAS APSKATE
// =========================

// SVARĪGI: šim jābūt PĒC /competitions/create
Route::get('/competitions/{competition}', [CompetitionController::class, 'show'])
    ->name('competitions.show');


// =========================
// ADMIN
// =========================

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin');

});