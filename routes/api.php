<?php

use App\Http\Controllers\Api\LedStatusController;
use App\Http\Controllers\DesperdicioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json([
        'usuario_id' => $request->attributes->get('usuario'),
    ]);
})->middleware('token.usuario');

Route::post('/cadastro_usuario', [UsuarioController::class, 'cadastro_usuario']);
Route::post('/login', [LoginController::class, 'autenticar'])->name('api.login');
Route::post('/logout', [LoginController::class, 'sair'])->middleware('token.usuario')->name('api.logout');
Route::post('/desperdicios', [DesperdicioController::class, 'salvar'])
    ->middleware('token.usuario')
    ->name('api.desperdicios.salvar');

Route::post('/desperdicios/peso', [DesperdicioController::class, 'salvarPeso'])
    ->middleware('token.usuario')
    ->name('api.desperdicios.peso');

Route::get('/led/status', [LedStatusController::class, 'show'])
    ->middleware('token.usuario')
    ->name('api.led.status');
