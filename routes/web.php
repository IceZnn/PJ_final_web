<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'formulario'])->name('login');
Route::view('/inicio', 'inicio')->name('inicio');
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::view('/controle-desperdicio', 'controle_desperdicio')->name('controle.desperdicio');
Route::get('/registrar-desperdicio', [App\Http\Controllers\DesperdicioController::class, 'registrarDesperdicioForm'])->name('desperdicio.registro');
Route::get('/desperdicios', [App\Http\Controllers\DesperdicioController::class, 'index'])->name('desperdicios.index');
Route::get('/desperdicios/{id}', [App\Http\Controllers\DesperdicioController::class, 'show'])->name('desperdicios.show');
Route::delete('/desperdicios/{id}', [App\Http\Controllers\DesperdicioController::class, 'destroy'])->name('desperdicios.destroy');
Route::get('/cadastro-usuario', [UsuarioController::class, 'cadastro_usuario_html'])->name('cadastro.usuario');
Route::post('/cadastro_usuario', [UsuarioController::class, 'cadastro_usuario']);