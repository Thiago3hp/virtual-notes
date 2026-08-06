<?php

use App\Http\Controllers\ChamadoCloseController;
use App\Http\Controllers\ChamadoCreateController;
use App\Http\Controllers\ChamadoDeleteController;
use App\Http\Controllers\ChamadoListController;
use App\Http\Controllers\ChamadoUpdateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDeleteController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserRestoreController;
use App\Http\Controllers\UserUpdateController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return Inertia::render('auth/Login', [
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
    Route::inertia('statistics', 'Statistics')->name('statistics');

    // Chamados (alimentados pelo bot do WhatsApp; editados/encerrados aqui)
    Route::get('/chamadoshome', [ChamadoListController::class, 'showList'])->name('chamadoshome');
    Route::get('/chamados/{id}', [ChamadoListController::class, 'showListId'])->name('chamados.show');
    Route::get('/chamados/search/status', [ChamadoListController::class, 'searchByStatus'])->name('chamados.search.status');
    Route::get('/chamados/search/setor', [ChamadoListController::class, 'searchBySetor'])->name('chamados.search.setor');
    Route::post('/chamados', [ChamadoCreateController::class, 'store'])->name('chamados.store');
    Route::put('/chamados/{id}', [ChamadoUpdateController::class, 'update'])->name('chamados.update');
    Route::patch('/chamados/{id}/fechar', [ChamadoCloseController::class, 'close'])->name('chamados.close');
    Route::delete('/chamados/{id}', [ChamadoDeleteController::class, 'destroy'])->name('chamados.destroy');

    // Users
    Route::get('/users', [UserListController::class, 'showList'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserUpdateController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserDeleteController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/restore', [UserRestoreController::class, 'restore'])->name('users.restore');
});

require __DIR__.'/settings.php';
