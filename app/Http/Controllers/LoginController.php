<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\TokenUsuario;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function formulario()
    {
        return view('Login');
    }

    public function autenticar(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string',
            'senha' => 'required|string',
        ]);

        $usuario = new Usuario;
        $usuario = $usuario->where('cpf', "=", $request->input('cpf'))->first();

        if (!$usuario || !Hash::check($request->input('senha'), $usuario->senha)) {
            return response()->json([
                'erro' => 's',
                'mensagem' => 'CPF ou senha inválidos.',
            ], 200);
        }

        $tokenUsuario = new TokenUsuario;
        $tokenUsuario->usuario_id = $usuario->id;
        $tokenUsuario->token = bin2hex(random_bytes(32));
        $tokenUsuario->valido_ate = now()->addDays(2);
        $tokenUsuario->save();

        return response()->json([
            'erro' => 'n',
            'mensagem' => 'Login realizado com sucesso!',
            'token' => $tokenUsuario->token,
            'usuario_id' => $usuario->id,
            'redirect' => route('inicio'),
        ], 200);
    }

    public function sair(Request $request)
    {
        TokenUsuario::where('token', $request->bearerToken())->delete();

        return response()->json([
            'mensagem' => 'Logout realizado com sucesso.',
        ]);
    }
}
