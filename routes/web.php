<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\CompetitionScorecardController;
use App\Http\Controllers\CompetitionRegistrationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiscController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PracticeController;


// =====================================================
// SĀKUMLAPA
// =====================================================

Route::get('/', [
    HomeController::class,
    'index'
])->name('home');


// =====================================================
// AUTORIZĀCIJA
// =====================================================

Route::get('/register', [
    AuthController::class,
    'showRegister'
])->name('register');

Route::post('/register', [
    AuthController::class,
    'register'
]);

Route::get('/login', [
    AuthController::class,
    'showLogin'
])->name('login');

Route::post('/login', [
    AuthController::class,
    'login'
]);

Route::post('/logout', [
    AuthController::class,
    'logout'
])->name('logout');


// =====================================================
// SPĒLĒTĀJI
// =====================================================

Route::get('/players', [
    PlayerController::class,
    'index'
])->name('players.index');

Route::get('/players/{user}', [
    PlayerController::class,
    'show'
])->name('players.show');


// =====================================================
// SACENSĪBU SARAKSTS
// =====================================================

Route::get('/competitions', [
    CompetitionController::class,
    'index'
])->name('competitions.index');


// =====================================================
// PARASTIE IESLOGOTIE LIETOTĀJI
// =====================================================

Route::middleware([
    'auth',
    'user',
])->group(function () {

    // -------------------------------------------------
    // SACENSĪBU IZVEIDE
    // -------------------------------------------------

    Route::get('/competitions/create', [
        CompetitionController::class,
        'create'
    ])->name('competitions.create');

    Route::post('/competitions', [
        CompetitionController::class,
        'store'
    ])->name('competitions.store');


    // -------------------------------------------------
    // PIETEIKŠANĀS SACENSĪBĀM
    // -------------------------------------------------

    Route::get('/competitions/{competition}/join', [
        CompetitionRegistrationController::class,
        'create'
    ])->name('competitions.join');

    Route::post('/competitions/{competition}/join', [
        CompetitionRegistrationController::class,
        'store'
    ])->name('competitions.join.store');


    // -------------------------------------------------
    // IZSTĀŠANĀS NO SACENSĪBĀM
    // -------------------------------------------------

    Route::post('/competitions/{competition}/leave', [
        CompetitionRegistrationController::class,
        'destroy'
    ])->name('competitions.leave');


    // -------------------------------------------------
    // SACENSĪBU REDIĢĒŠANA
    // -------------------------------------------------

    Route::get('/competitions/{competition}/edit', [
        CompetitionController::class,
        'edit'
    ])->name('competitions.edit');

    Route::put('/competitions/{competition}', [
        CompetitionController::class,
        'update'
    ])->name('competitions.update');


    // -------------------------------------------------
    // SACENSĪBU DZĒŠANA
    // -------------------------------------------------

    Route::delete('/competitions/{competition}', [
        CompetitionController::class,
        'destroy'
    ])->name('competitions.destroy');


    // -------------------------------------------------
    // SACENSĪBU SCORECARD
    // -------------------------------------------------

    Route::get('/competitions/{competition}/scorecard', [
        CompetitionScorecardController::class,
        'show'
    ])->name('competitions.scorecard');

    Route::post('/competitions/{competition}/scorecard', [
        CompetitionScorecardController::class,
        'store'
    ])->name('competitions.scorecard.store');


    // =================================================
    // PRACTICE APĻI
    // =================================================

    Route::get('/practice', [
        PracticeController::class,
        'index'
    ])->name('practice.index');

    Route::post('/practice', [
        PracticeController::class,
        'store'
    ])->name('practice.store');

    Route::get('/practice/{practiceRound}/scorecard', [
        PracticeController::class,
        'scorecard'
    ])->name('practice.scorecard');

    Route::post('/practice/{practiceRound}/scorecard', [
        PracticeController::class,
        'storeHoleResult'
    ])->name('practice.scorecard.store');

    Route::post('/practice/{practiceRound}/finish', [
        PracticeController::class,
        'finish'
    ])->name('practice.finish');

    Route::delete('/practice/{practiceRound}', [
        PracticeController::class,
        'destroy'
    ])->name('practice.destroy');


    // -------------------------------------------------
    // PROFILS
    // -------------------------------------------------

    Route::get('/profile', [
        ProfileController::class,
        'index'
    ])->name('profile');


    // -------------------------------------------------
    // PROFILA BILDE
    // -------------------------------------------------

    Route::put('/profile/picture', [
        ProfileController::class,
        'updateProfilePicture'
    ])->name('profile.picture.update');

    Route::delete('/profile/picture', [
        ProfileController::class,
        'deleteProfilePicture'
    ])->name('profile.picture.delete');


    // -------------------------------------------------
    // DISKI
    // -------------------------------------------------

    Route::get('/profile/discs', [
        DiscController::class,
        'index'
    ])->name('profile.discs.index');

    Route::get('/profile/discs/create', [
        DiscController::class,
        'create'
    ])->name('profile.discs.create');

    Route::post('/profile/discs', [
        DiscController::class,
        'store'
    ])->name('profile.discs.store');

    Route::get('/profile/discs/{disc}/edit', [
        DiscController::class,
        'edit'
    ])->name('profile.discs.edit');

    Route::put('/profile/discs/{disc}', [
        DiscController::class,
        'update'
    ])->name('profile.discs.update');

    Route::delete('/profile/discs/{disc}', [
        DiscController::class,
        'destroy'
    ])->name('profile.discs.destroy');
});


// =====================================================
// VIENAS SACENSĪBAS
// =====================================================

Route::get('/competitions/{competition}', [
    CompetitionController::class,
    'show'
])->name('competitions.show');


// =====================================================
// ADMIN PANELIS
// =====================================================

Route::middleware([
    'auth',
    'admin',
])
    ->prefix('admin')
    ->group(function () {

        Route::get('/', [
            AdminController::class,
            'index'
        ])->name('admin');

        Route::get('/users', [
            AdminController::class,
            'users'
        ])->name('admin.users');

        Route::put('/users/{user}', [
            AdminController::class,
            'updateUser'
        ])->name('admin.users.update');

        Route::delete('/users/{user}', [
            AdminController::class,
            'destroyUser'
        ])->name('admin.users.destroy');


        // ---------------------------------------------
        // TRASES
        // ---------------------------------------------

        Route::get('/courses', [
            CourseController::class,
            'index'
        ])->name('courses.index');

        Route::get('/courses/create', [
            CourseController::class,
            'create'
        ])->name('courses.create');

        Route::post('/courses', [
            CourseController::class,
            'store'
        ])->name('courses.store');

        Route::get('/courses/{course}/edit', [
            CourseController::class,
            'edit'
        ])->name('courses.edit');

        Route::put('/courses/{course}', [
            CourseController::class,
            'update'
        ])->name('courses.update');

        Route::delete('/courses/{course}', [
            CourseController::class,
            'destroy'
        ])->name('courses.destroy');
    });