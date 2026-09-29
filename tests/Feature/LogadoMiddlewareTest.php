<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LogadoMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_redireciona_para_login_quando_nao_ha_cookie(): void
    {
        $this->get(route('inicio'))
            ->assertRedirect(route('login'));
    }

    public function test_permite_acesso_com_cookie_de_token_valido(): void
    {
        DB::table('usuario')->insert([
            'nome' => 'Usuario de teste',
            'email' => 'middleware@example.com',
            'cpf' => '12345678901',
            'senha' => 'senha',
            'escola' => 'Escola de teste',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('token_usuario')->insert([
            'usuario_id' => 1,
            'token' => 'token-de-teste',
            'valido_ate' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withCookie('token', 'token-de-teste')
            ->get(route('inicio'))
            ->assertOk();
    }

    public function test_login_web_define_cookie_de_token(): void
    {
        DB::table('usuario')->insert([
            'nome' => 'Usuario de teste',
            'email' => 'login-middleware@example.com',
            'cpf' => '12345678901',
            'senha' => \Illuminate\Support\Facades\Hash::make('senha-segura'),
            'escola' => 'Escola de teste',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post(route('login.autenticar'), [
            'cpf' => '12345678901',
            'senha' => 'senha-segura',
        ])->assertOk()
            ->assertCookie('token');
    }
}