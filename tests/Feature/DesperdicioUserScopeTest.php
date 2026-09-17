<?php

namespace Tests\Feature;

use App\Models\Desperdicio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DesperdicioUserScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_filtra_apenas_registros_do_usuario_atual(): void
    {
        DB::table('usuario')->insert([
            ['id' => 1, 'nome' => 'Usuario 1', 'email' => 'u1@example.com', 'cpf' => '111', 'senha' => '123', 'escola' => 'Escola A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nome' => 'Usuario 2', 'email' => 'u2@example.com', 'cpf' => '222', 'senha' => '123', 'escola' => 'Escola B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Desperdicio::create([
            'usuario_id' => 1,
            'cardapio' => 'Arroz e feijão',
            'periodo' => 'almoco',
            'salas' => ['fund1'],
            'quantidade_preparada' => 100,
            'maximo_desperdicio' => 15,
            'peso_desperdicio' => 10,
        ]);

        Desperdicio::create([
            'usuario_id' => 2,
            'cardapio' => 'Pão e leite',
            'periodo' => 'cafe',
            'salas' => ['em'],
            'quantidade_preparada' => 100,
            'maximo_desperdicio' => 10,
            'peso_desperdicio' => 20,
        ]);

        $registros = Desperdicio::query()->forUsuario(1)->get();

        $this->assertCount(1, $registros);
        $this->assertSame(1, $registros->first()->usuario_id);
    }

    public function test_calcula_percentual_real_do_desperdicio_baseado_no_peso(): void
    {
        $registro = new Desperdicio([
            'quantidade_preparada' => 80,
            'peso_desperdicio' => 12,
            'maximo_desperdicio' => 15,
        ]);

        $this->assertSame(15.0, round($registro->percentualReal(), 1));
        $this->assertSame('Dentro da meta', $registro->statusDesperdicio());
    }
}
