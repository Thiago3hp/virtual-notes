<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\TelaInicialController;
use App\Http\Controllers\TaskViewController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TaskCreateController;


Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('taskshome', 'TasksHome')->name('taskshome');
    Route::post('/tasks', [TaskCreateController::class, 'store']);
    Route::inertia('statistics', 'Statistics')->name('statistics');
});

require __DIR__.'/settings.php';
