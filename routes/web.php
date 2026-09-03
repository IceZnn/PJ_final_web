<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('cadastro_usuario');
});

Route::get('/cadastro-usuario', [UsuarioController::class, 'cadastro_usuario_html']);
Route::post('/cadastro_usuario', [UsuarioController::class, 'cadastro_usuario']);