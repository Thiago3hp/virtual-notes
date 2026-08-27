<?php

use App\Http\Controllers\ChamadoCloseController;
use App\Http\Controllers\ChamadoCreateController;
use App\Http\Controllers\ChamadoDeleteController;
use App\Http\Controllers\ChamadoListController;
use App\Http\Controllers\ChamadoUpdateController;
use App\Http\Controllers\ClienteCreateController;
use App\Http\Controllers\ClienteDeleteController;
use App\Http\Controllers\ClienteListController;
use App\Http\Controllers\ClienteUpdateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipamentoCreateController;
use App\Http\Controllers\EquipamentoDeleteController;
use App\Http\Controllers\EquipamentoListController;
use App\Http\Controllers\EquipamentoUpdateController;
use App\Http\Controllers\NumeroVerificationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDeleteController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserRestoreController;
use App\Http\Controllers\UserUpdateController;
use App\Http\Controllers\VerificationCodeController;
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

Route::middleware('auth')->group(function () {
    Route::get('/verify-code', [VerificationCodeController::class, 'show'])->name('verification.notice');
    Route::post('/verify-code', [VerificationCodeController::class, 'confirmar'])->name('verification.code.confirm');
    Route::post('/verify-code/resend', [VerificationCodeController::class, 'reenviar'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::middleware('verified')->group(function () {
        Route::get('/verify-numero', [NumeroVerificationController::class, 'show'])->name('numero.verification.notice');
        Route::post('/verify-numero', [NumeroVerificationController::class, 'confirmar'])->name('numero.verification.confirm');
        Route::post('/verify-numero/resend', [NumeroVerificationController::class, 'reenviar'])
            ->middleware('throttle:6,1')
            ->name('numero.verification.send');
    });
});

Route::middleware(['auth', 'verified', 'numero.verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

    // Chamados (alimentados pelo bot do WhatsApp; editados/encerrados aqui)
    Route::get('/chamadoshome', [ChamadoListController::class, 'showList'])->name('chamadoshome');
    Route::get('/chamados/{id}', [ChamadoListController::class, 'showListId'])->name('chamados.show');
    Route::get('/chamados/search/status', [ChamadoListController::class, 'searchByStatus'])->name('chamados.search.status');
    Route::get('/chamados/search/setor', [ChamadoListController::class, 'searchBySetor'])->name('chamados.search.setor');
    Route::post('/chamados', [ChamadoCreateController::class, 'store'])->name('chamados.store');
    Route::put('/chamados/{id}', [ChamadoUpdateController::class, 'update'])->name('chamados.update');
    Route::patch('/chamados/{id}/fechar', [ChamadoCloseController::class, 'close'])->name('chamados.close');
    Route::delete('/chamados/{id}', [ChamadoDeleteController::class, 'destroy'])->name('chamados.destroy');

    // Clientes (setores atendidos)
    Route::get('/clientes', [ClienteListController::class, 'showList'])->name('clientes');
    Route::post('/clientes', [ClienteCreateController::class, 'store'])->name('clientes.store');
    Route::put('/clientes/{id}', [ClienteUpdateController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{id}', [ClienteDeleteController::class, 'destroy'])->name('clientes.destroy');

    // Equipamentos
    Route::get('/equipamentos', [EquipamentoListController::class, 'showList'])->name('equipamentos');
    Route::post('/equipamentos', [EquipamentoCreateController::class, 'store'])->name('equipamentos.store');
    Route::put('/equipamentos/{id}', [EquipamentoUpdateController::class, 'update'])->name('equipamentos.update');
    Route::delete('/equipamentos/{id}', [EquipamentoDeleteController::class, 'destroy'])->name('equipamentos.destroy');

    // Users
    Route::get('/users', [UserListController::class, 'showList'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserUpdateController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserDeleteController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/restore', [UserRestoreController::class, 'restore'])->name('users.restore');
});

require __DIR__.'/settings.php';
