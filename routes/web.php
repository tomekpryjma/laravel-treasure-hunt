<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GameSessionController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StepController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Middleware handled in controller.
Route::prefix('game')->group(function () {
    Route::post('/store', [GameController::class, 'store'])->name('game.store');

    // TODO: payment gateway
    Route::post('/register', [GameController::class, 'register'])->name('game.register');
});

Route::prefix('step')->group(function () {
    Route::post('/store/{game?}', [StepController::class, 'store'])->name('step.store');
    Route::post('/update/{step}', [StepController::class, 'update'])->name('step.update');
});

Route::prefix('game-session')->group(function () {
    Route::get('/', [GameSessionController::class, 'login']);
    Route::get('/test', [GameSessionController::class, 'test'])->name('game-session.test');
});

require __DIR__ . '/auth.php';
