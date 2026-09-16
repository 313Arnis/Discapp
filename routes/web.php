<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiscController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;


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

Route::get('/competitions/{competition}', [CompetitionController::class, 'show'])
    ->name('competitions.show');


// =========================
// SACENSĪBU REZULTĀTI
// =========================

Route::post(
    '/competitions/{competition}/results',
    [CompetitionController::class, 'storeResult']
)->name('competitions.results.store');


// =========================
// ADMIN
// =========================

Route::middleware(['auth', 'admin'])->group(function () {

    // -------------------------
    // ADMIN PANELIS
    // -------------------------

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin');


    // -------------------------
    // TRASĒS
    // -------------------------

    Route::get('/courses', [CourseController::class, 'index'])
        ->name('courses.index');

    Route::get('/courses/create', [CourseController::class, 'create'])
        ->name('courses.create');

    Route::post('/courses', [CourseController::class, 'store'])
        ->name('courses.store');

    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])
        ->name('courses.edit');

    Route::put('/courses/{course}', [CourseController::class, 'update'])
        ->name('courses.update');

    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
        ->name('courses.destroy');
});