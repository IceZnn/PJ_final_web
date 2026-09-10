<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;

Route::get('/user', function (Request $request) {
    return response()->json([
        'usuario_id' => $request->attributes->get('usuario'),
    ]);
})->middleware('token.usuario');

Route::post('/cadastro_usuario', [UsuarioController::class, 'cadastro_usuario']);
Route::post('/login', [LoginController::class, 'autenticar'])->name('api.login');
Route::post('/logout', [LoginController::class, 'sair'])->middleware('token.usuario')->name('api.logout');