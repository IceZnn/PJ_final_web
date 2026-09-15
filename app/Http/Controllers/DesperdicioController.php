<?php

namespace App\Http\Controllers;

use App\Models\Desperdicio;
use Illuminate\Http\Request;

class DesperdicioController extends Controller
{
    public function salvar(Request $request)
    {
        $dados = $request->validate([
            'cardapio' => 'required|string',
            'periodo' => 'required|in:cafe,almoco,cafe_tarde',
            'salas' => 'required|array|min:1',
            'salas.*' => 'string|in:fund1,fund2,em,senai',
            'quantidade' => 'required|numeric|min:0',
            'desperdicio' => 'required|integer|min:0|max:100',
            'observacoes' => 'nullable|string',
        ]);

        $desperdicio = new Desperdicio;
        $desperdicio->usuario_id = $request->attributes->get('usuario');
        $desperdicio->cardapio = $dados['cardapio'];
        $desperdicio->periodo = $dados['periodo'];
        $desperdicio->salas = $dados['salas'];
        $desperdicio->quantidade_preparada = $dados['quantidade'];
        $desperdicio->maximo_desperdicio = $dados['desperdicio'];
        $desperdicio->observacoes = $dados['observacoes'] ?? null;
        $desperdicio->save();

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Controle de desperdício salvo com sucesso.',
        ], 201);
    }
}