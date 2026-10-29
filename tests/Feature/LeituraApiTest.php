<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeituraApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_reading_sent_without_cell_values(): void
    {
        $response = $this->postJson('/api/leituras', [
            'device_id' => 'balanca-01',
            'total' => 12.34,
        ]);

        $response->assertCreated()
            ->assertJson([
                'status' => 'ok',
            ])
            ->assertJsonStructure(['id', 'status']);

        $this->assertDatabaseHas('leituras', [
            'id' => $response->json('id'),
            'device_id' => 'balanca-01',
            'cell_1' => null,
            'cell_2' => null,
            'cell_3' => null,
            'cell_4' => null,
            'total' => 12.34,
        ]);
    }

    public function test_it_still_accepts_optional_cell_values(): void
    {
        $response = $this->postJson('/api/leituras', [
            'device_id' => 'balanca-01',
            'cell_1' => 1.1,
            'cell_2' => 2.2,
            'cell_3' => 3.3,
            'cell_4' => 4.4,
            'total' => 11.0,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('leituras', [
            'device_id' => 'balanca-01',
            'cell_1' => 1.1,
            'cell_2' => 2.2,
            'cell_3' => 3.3,
            'cell_4' => 4.4,
            'total' => 11.0,
        ]);
    }
}
