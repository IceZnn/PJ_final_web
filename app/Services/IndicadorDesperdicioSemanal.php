<?php

namespace App\Services;

use App\Models\Desperdicio;
use Carbon\Carbon;

class IndicadorDesperdicioSemanal
{
    public function paraUsuario(int $usuarioId): array
    {
        $inicio = now()->startOfWeek(Carbon::MONDAY);
        $fim = $inicio->copy()->endOfWeek(Carbon::SUNDAY);

        $registros = Desperdicio::query()
            ->forUsuario($usuarioId)
            ->whereNotNull('peso_desperdicio')
            ->whereBetween('created_at', [$inicio, $fim])
            ->get(['peso_desperdicio']);

        $media = (float) ($registros->avg(fn (Desperdicio $registro) => (float) $registro->peso_desperdicio) ?? 0);

        if ($media >= 1 && $media < 6) {
            $cor = 'verde';
        } elseif ($media >= 6 && $media <= 12) {
            $cor = 'amarelo';
        } else {
            $cor = 'vermelho';
        }

        return [
            'media_semanal_kg' => round($media, 2),
            'cor' => $cor,
            'registros_considerados' => $registros->count(),
            'semana' => [
                'inicio' => $inicio->toDateString(),
                'fim' => $fim->toDateString(),
            ],
        ];
    }
}
