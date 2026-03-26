<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\TelaInicialController;


Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::get('/tela-inicial', [TelaInicialController::class, 'index'])
    ->name('TelaInicial');

// Route::get('/usuario.store'),
require __DIR__.'/settings.php';
