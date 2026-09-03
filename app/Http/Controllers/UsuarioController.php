<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function cadastro_usuario_html(Request $request)
    {
        return view('cadastro_usuario');
    }

    public function cadastro_usuario(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required',
            'cpf' => 'nullable|string|max:14',
            'senha' => 'required|string|min:6',
            'escola' => 'required|string|max:255',
        ]);

        $usuario = new Usuario;
        if($usuario->where('email',"=",$request->input('email'))->exists()) {
            return response()->json(['erro'=>'s','mensagem'=>'O email já está em uso.'],200);
        }

        try{
            $usuario->nome = $request->input('nome');
            $usuario->email = $request->input('email');
            $usuario->cpf = $request->input('cpf');
            $usuario->senha = bcrypt($request->senha);
            $usuario->escola = $request->input('escola');
            $usuario->save();

            return response()->json(['erro'=>'n','mensagem'=>'Usuário cadastrado com sucesso.'],200);
        } catch (\Exception $e) {
            return response()->json(['erro'=>'s','mensagem'=>'Erro ao cadastrar usuário: ' . $e->getMessage()],500);
        }
    }
}
