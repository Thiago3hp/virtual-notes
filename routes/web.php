<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\TelaInicialController;
use App\Http\Controllers\TaskViewController;
use App\Http\Controllers\UsuarioController;


Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

// Route::get('/Welcome', [TaskViewController::class, 'index'])->name('welcome');

Route::get('/home-tasks', [TaskViewController::class, 'index']);
Route::get('/login', [TelaInicialController::class, 'index']);
// Route::get('usuario.store', [UsuarioController::class, 'store'])->name('usuario.store');

// Route::get('/usuario.store'),
require __DIR__.'/settings.php';
