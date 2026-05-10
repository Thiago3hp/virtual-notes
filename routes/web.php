<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TaskCreateController;
use App\Http\Controllers\TaskUpdateConrtoller;
use inertia\Inertia;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return Inertia::render('auth/Login',[
        'canResetPassword' => true,
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->middleware('guest')->name('login');

if (Features::enabled(Features::registration())) {
    Route::get('/register', function () {
        return Inertia::render('auth/Register');
    })->middleware('guest')->name('register');
}

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::post('/tasks', [TaskCreateController::class, 'store']);
    Route::get('/taskshome', [TaskListController::class, 'showList'])->name ('taskshome');
    Route::put('/tasks_update',[TaskUpdateController::class, 'update']);
    Route::inertia('statistics', 'Statistics')->name('statistics');
});

require __DIR__.'/settings.php';
