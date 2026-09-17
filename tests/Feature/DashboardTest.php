<?php

namespace Tests\Feature;

use App\Models\Desperdicio;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_real_metric_data(): void
    {
        $usuario = Usuario::create([
            'nome' => 'Teste User',
            'email' => 'teste@teste.com',
            'cpf' => '12345678909',
            'senha' => bcrypt('senha123'),
            'escola' => 'Escola Teste',
        ]);

        Desperdicio::create([
            'usuario_id' => $usuario->id,
            'cardapio' => 'Arroz e feijão',
            'periodo' => 'almoco',
            'salas' => ['fund1', 'senai'],
            'quantidade_preparada' => 25.50,
            'maximo_desperdicio' => 12,
            'peso_desperdicio' => 3.25,
            'observacoes' => 'Teste',
            'created_at' => now()->subMonth()->startOfMonth(),
        ]);

        $response = $this->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('25,50');
        $response->assertSee('3,25');
        $response->assertSee('87,3%');
    }

    public function test_dashboard_period_chart_uses_actual_waste_per_school_group(): void
    {
        $usuario = Usuario::create([
            'nome' => 'Teste User 2',
            'email' => 'teste2@teste.com',
            'cpf' => '12345678908',
            'senha' => bcrypt('senha123'),
            'escola' => 'Escola Teste',
        ]);

        Desperdicio::create([
            'usuario_id' => $usuario->id,
            'cardapio' => 'Refeição A',
            'periodo' => 'almoco',
            'salas' => ['fund1'],
            'quantidade_preparada' => 100,
            'maximo_desperdicio' => 12,
            'peso_desperdicio' => 3,
            'observacoes' => 'Teste',
            'created_at' => now()->subDay(),
        ]);

        Desperdicio::create([
            'usuario_id' => $usuario->id,
            'cardapio' => 'Refeição B',
            'periodo' => 'cafe',
            'salas' => ['em'],
            'quantidade_preparada' => 40,
            'maximo_desperdicio' => 12,
            'peso_desperdicio' => 18,
            'observacoes' => 'Teste',
            'created_at' => now()->subDay(),
        ]);

        $response = $this->get(route('dashboard'));
        $periodData = $response->original->getData()['periodData'];

        $fundamental = collect($periodData)->firstWhere('label', 'Fundamental');
        $medio = collect($periodData)->firstWhere('label', 'Médio');

        $this->assertNotNull($fundamental);
        $this->assertNotNull($medio);
        $this->assertGreaterThan($fundamental['height'], $medio['height']);
    }
}
