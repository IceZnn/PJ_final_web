<?php

namespace App\Http\Controllers;

use App\Models\Desperdicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class DashboardController extends Controller
{
    public function index()
    {
        $usuarioId = request()->attributes->get('usuario') ?? request()->query('usuario_id');

        $registros = Desperdicio::query()
            ->when($usuarioId, fn ($query) => $query->forUsuario((int) $usuarioId))
            ->orderBy('created_at')
            ->get();

        $registrosComPeso = $registros->filter(fn ($registro) => $registro->peso_desperdicio !== null);
        $totalPreparado = (float) $registros->sum('quantidade_preparada');
        $pesoRealTotal = (float) $registrosComPeso->sum('peso_desperdicio');
        $percentualPerdaMedia = $registrosComPeso->isEmpty() || $totalPreparado <= 0
            ? 0
            : round(($pesoRealTotal / $totalPreparado) * 100, 1);
        $aproveitamentoMedio = round(100 - $percentualPerdaMedia, 1);
        $alertas = $registrosComPeso->filter(fn ($registro) => $registro->percentualReal() > (float) $registro->maximo_desperdicio)->count();

        $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $monthly = [];
        $valorMaximoMes = 1;

        foreach (range(5, 1) as $offset) {
            $date = now()->subMonths($offset - 1);
            $label = $meses[$date->month - 1];
            $valor = (float) $registrosComPeso
                ->filter(fn ($registro) => $registro->created_at && $registro->created_at->month === $date->month && $registro->created_at->year === $date->year)
                ->sum(fn ($registro) => $registro->percentualReal());

            $monthly[] = [
                'label' => $label,
                'valor' => $valor,
            ];

            $valorMaximoMes = max($valorMaximoMes, $valor);
        }

        $monthly = collect($monthly)->map(function ($item) use ($valorMaximoMes) {
            return [
                'label' => $item['label'],
                'height' => $valorMaximoMes > 0 ? round(($item['valor'] / $valorMaximoMes) * 100, 1) : 0,
            ];
        })->values()->all();

        $periodoBase = [
            'Fundamental' => 0,
            'Médio' => 0,
            'Senai' => 0,
        ];

        foreach ($registros as $registro) {
            foreach ($registro->salas ?? [] as $sala) {
                $pesoDesperdicio = (float) ($registro->peso_desperdicio ?? 0);

                if (str_contains($sala, 'fund')) {
                    $periodoBase['Fundamental'] += $pesoDesperdicio;
                } elseif ($sala === 'em') {
                    $periodoBase['Médio'] += $pesoDesperdicio;
                } elseif ($sala === 'senai') {
                    $periodoBase['Senai'] += $pesoDesperdicio;
                }
            }
        }

        $periodoMaximo = max(array_values($periodoBase)) ?: 1;
        $periodoData = collect(array_keys($periodoBase))->map(function ($label) use ($periodoBase, $periodoMaximo) {
            return [
                'label' => $label,
                'height' => $periodoMaximo > 0 ? round(($periodoBase[$label] / $periodoMaximo) * 100, 1) : 0,
            ];
        })->all();

        $turnos = collect([
            'cafe' => 'Café',
            'almoco' => 'Almoço',
            'cafe_tarde' => 'Café tarde',
        ])->map(function ($label, $chave) use ($registrosComPeso) {
            $dados = $registrosComPeso->where('periodo', $chave);
            $atual = $dados->isEmpty() ? 0 : round(
                $dados->avg(fn ($registro) => $registro->percentualReal()),
                1
            );
            $meta = 10;

            if ($chave === 'almoco') {
                $meta = 12;
            }

            if ($chave === 'cafe_tarde') {
                $meta = 8;
            }

            return [
                'turno' => $label,
                'meta' => $meta,
                'atual' => $atual,
                'status' => $atual <= $meta ? 'Dentro da meta' : 'Atenção',
                'statusClass' => $atual <= $meta ? 'success' : 'warning',
            ];
        })->values()->all();

        $turnoCritico = collect($turnos)->sortByDesc('atual')->first();

        return view('dashboard', [
            'aproveitamentoMedio' => number_format($aproveitamentoMedio, 1, ',', '.'),
            'percentualPerdaMedia' => number_format($percentualPerdaMedia, 1, ',', '.'),
            'totalPreparado' => number_format($totalPreparado, 2, ',', '.'),
            'pesoRealTotal' => number_format($pesoRealTotal, 2, ',', '.'),
            'alertas' => $alertas,
            'monthlyData' => $monthly,
            'periodData' => $periodoData,
            'turnos' => $turnos,
            'turnoCritico' => $turnoCritico,
            'marginTrend' => $registrosComPeso->isEmpty() ? 'Sem peso registrado' : 'Dados atualizados em ' . now()->format('d/m/Y'),
        ]);
    }
}
