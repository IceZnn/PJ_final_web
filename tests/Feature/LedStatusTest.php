<?php

namespace Tests\Feature;

use App\Models\Desperdicio;
use App\Models\TokenUsuario;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LedStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_calcula_media_apenas_dos_registros_da_semana_e_do_usuario(): void
    {
        $usuario = $this->criarUsuario('led@example.com', '12345678901');
        $outroUsuario = $this->criarUsuario('outro@example.com', '12345678902');

        $this->criarRegistro($usuario, 4, now()->startOfWeek()->addDay());
        $this->criarRegistro($usuario, 8, now()->startOfWeek()->addDays(2));
        $this->criarRegistro($usuario, 20, now()->subWeek());
        $this->criarRegistro($outroUsuario, 20, now());

        $response = $this->withToken($this->criarToken($usuario))
            ->getJson(route('api.led.status'));

        $response->assertOk()
            ->assertJsonPath('media_semanal_kg', 6)
            ->assertJsonPath('cor', 'amarelo')
            ->assertJsonPath('registros_considerados', 2);
    }

    public function test_api_classifica_media_nos_limites_de_cor(): void
    {
        $usuario = $this->criarUsuario('cores@example.com', '12345678903');
        $token = $this->criarToken($usuario);

        foreach ([[2, 'verde'], [6, 'amarelo'], [13, 'vermelho']] as [$peso, $corEsperada]) {
            Desperdicio::query()->delete();
            $this->criarRegistro($usuario, $peso, now());

            $this->withToken($token)
                ->getJson(route('api.led.status'))
                ->assertOk()
                ->assertJsonPath('cor', $corEsperada);
        }
    }

    public function test_api_retorna_vermelho_quando_a_media_e_zero(): void
    {
        $usuario = $this->criarUsuario('sem-registros@example.com', '12345678904');

        $this->withToken($this->criarToken($usuario))
            ->getJson(route('api.led.status'))
            ->assertOk()
            ->assertJsonPath('media_semanal_kg', 0)
            ->assertJsonPath('cor', 'vermelho')
            ->assertJsonPath('registros_considerados', 0);
    }

    public function test_api_exige_token_valido(): void
    {
        $this->getJson(route('api.led.status'))->assertUnauthorized();
    }

    private function criarUsuario(string $email, string $cpf): Usuario
    {
        return Usuario::create([
            'nome' => 'Usuário IoT',
            'email' => $email,
            'cpf' => $cpf,
            'senha' => bcrypt('senha123'),
            'escola' => 'Escola Teste',
        ]);
    }

    private function criarRegistro(Usuario $usuario, float $peso, $data): void
    {
        $registro = Desperdicio::create([
            'usuario_id' => $usuario->id,
            'cardapio' => 'Refeição teste',
            'periodo' => 'almoco',
            'salas' => ['fund1'],
            'quantidade_preparada' => 20,
            'maximo_desperdicio' => 12,
            'peso_desperdicio' => $peso,
        ]);

        $registro->created_at = $data;
        $registro->save();
    }

    private function criarToken(Usuario $usuario): string
    {
        $token = bin2hex(random_bytes(16));

        TokenUsuario::create([
            'usuario_id' => $usuario->id,
            'token' => $token,
            'valido_ate' => now()->addDay(),
        ]);

        return $token;
    }
}
