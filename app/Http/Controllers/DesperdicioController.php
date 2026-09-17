<?php

namespace App\Http\Controllers;

use App\Models\Desperdicio;
use Illuminate\Http\Request;

class DesperdicioController extends Controller
{
    public function index(Request $request)
    {
        $usuarioId = $request->attributes->get('usuario') ?? $request->query('usuario_id');

        $registros = Desperdicio::query()
            ->when($usuarioId, fn ($query) => $query->forUsuario((int) $usuarioId))
            ->orderByDesc('created_at')
            ->get();

        return view('desperdicios_index', compact('registros'));
    }

    public function show(Request $request, int $id)
    {
        $usuarioId = $request->attributes->get('usuario') ?? $request->query('usuario_id');

        $registro = Desperdicio::query()
            ->when($usuarioId, fn ($query) => $query->forUsuario((int) $usuarioId))
            ->findOrFail($id);

        return view('desperdicio_detalhes', compact('registro'));
    }

    public function destroy(Request $request, int $id)
    {
        $usuarioId = $request->attributes->get('usuario') ?? $request->query('usuario_id');

        $registro = Desperdicio::query()
            ->when($usuarioId, fn ($query) => $query->forUsuario((int) $usuarioId))
            ->findOrFail($id);

        $registro->delete();

        return redirect()->route('desperdicios.index')->with('sucesso', 'Registro removido com sucesso.');
    }

    public function salvar(Request $request)
    {
        $usuarioId = $request->attributes->get('usuario') ?? $request->query('usuario_id');

        if (! $usuarioId) {
            return response()->json([
                'mensagem' => 'Usuário não autenticado.',
            ], 401);
        }

        $dados = $request->validate([
            'cardapio' => 'required|string',
            'periodo' => 'required|in:cafe,almoco,cafe_tarde',
            'salas' => 'required|array|min:1',
            'salas.*' => 'string|in:fund1,fund2,em,senai',
            'quantidade' => 'required|numeric|min:0',
            'desperdicio' => 'required|integer|min:0|max:100',
            'peso_desperdicio' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string',
        ]);

        $desperdicio = new Desperdicio;
        $desperdicio->usuario_id = $usuarioId;
        $desperdicio->cardapio = $dados['cardapio'];
        $desperdicio->periodo = $dados['periodo'];
        $desperdicio->salas = $dados['salas'];
        $desperdicio->quantidade_preparada = $dados['quantidade'];
        $desperdicio->maximo_desperdicio = $dados['desperdicio'];
        $desperdicio->peso_desperdicio = $dados['peso_desperdicio'] ?? null;
        $desperdicio->observacoes = $dados['observacoes'] ?? null;
        $desperdicio->save();

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Refeição e meta salvos com sucesso. Agora registre o peso do desperdício real.',
            'refeicao_id' => $desperdicio->id,
        ], 201);
    }

    public function registrarDesperdicioForm(Request $request)
    {
        $usuarioId = $request->attributes->get('usuario') ?? $request->query('usuario_id');

        $refeicoes = Desperdicio::query()
            ->when($usuarioId, fn ($query) => $query->forUsuario((int) $usuarioId))
            ->orderByDesc('created_at')
            ->get();

        return view('registrar_desperdicio', compact('refeicoes'));
    }

    public function salvarPeso(Request $request)
    {
        $usuarioId = $request->attributes->get('usuario') ?? $request->query('usuario_id');

        if (! $usuarioId) {
            return response()->json([
                'mensagem' => 'Usuário não autenticado.',
            ], 401);
        }

        $dados = $request->validate([
            'refeicao_id' => 'required|exists:desperdicio,id',
            'peso_desperdicio' => 'required|numeric|min:0',
        ]);

        $registro = Desperdicio::query()
            ->forUsuario((int) $usuarioId)
            ->findOrFail($dados['refeicao_id']);

        $registro->peso_desperdicio = (float) $dados['peso_desperdicio'];
        $registro->save();

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Peso do desperdício registrado com sucesso.',
        ], 200);
    }
}