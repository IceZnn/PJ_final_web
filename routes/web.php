<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'formulario'])->name('login');
Route::view('/inicio', 'inicio')->name('inicio');
Route::get('/cadastro-usuario', [UsuarioController::class, 'cadastro_usuario_html'])->name('cadastro.usuario');
Route::post('/cadastro_usuario', [UsuarioController::class, 'cadastro_usuario']);