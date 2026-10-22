<?php

namespace Database\Seeders;

use App\Models\Leitura;
use Illuminate\Database\Seeder;

class LeituraSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $cells = [
                fake()->randomFloat(2, -100, 100),
                fake()->randomFloat(2, -100, 100),
                fake()->randomFloat(2, -100, 100),
                fake()->randomFloat(2, -100, 100),
            ];

            Leitura::create([
                'device_id' => 'balanca-teste',
                'cell_1' => $cells[0],
                'cell_2' => $cells[1],
                'cell_3' => $cells[2],
                'cell_4' => $cells[3],
                'total' => round(array_sum($cells), 2),
            ]);
        }
    }
}
