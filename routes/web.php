<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GameSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

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

Route::prefix('game-session')->group(function () {
    Route::get('/lobby/{sessionCode?}', [GameSessionController::class, 'lobby'])->name('game-session.lobby');
    Route::post('/show/{sessionCode}', [GameSessionController::class, 'show'])->name('game-session.show');
});

Route::middleware('auth')->prefix('step')->group(function () {
    Route::post('/store/{game?}', [StepController::class, 'store'])->name('step.store');
    Route::post('/update/{step}', [StepController::class, 'update'])->name('step.update');
});
