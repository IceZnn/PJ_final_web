<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desperdicio extends Model
{
    protected $table = 'desperdicio';

    protected $fillable = [
        'usuario_id',
        'cardapio',
        'periodo',
        'salas',
        'quantidade_preparada',
        'maximo_desperdicio',
        'peso_desperdicio',
        'observacoes',
    ];

    protected $casts = [
        'salas' => 'array',
        'quantidade_preparada' => 'decimal:2',
        'maximo_desperdicio' => 'integer',
        'peso_desperdicio' => 'decimal:2',
    ];

    public function scopeForUsuario($query, int $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function percentualReal(): float
    {
        $quantidadePreparada = (float) ($this->quantidade_preparada ?? 0);
        $pesoDesperdicio = (float) ($this->peso_desperdicio ?? 0);

        if ($quantidadePreparada <= 0) {
            return 0.0;
        }

        return ($pesoDesperdicio / $quantidadePreparada) * 100;
    }

    public function percentualMeta(): float
    {
        $quantidadePreparada = (float) ($this->quantidade_preparada ?? 0);
        $meta = (float) ($this->maximo_desperdicio ?? 0);

        if ($quantidadePreparada <= 0) {
            return 0.0;
        }

        return ($quantidadePreparada * $meta) / 100;
    }

    public function statusDesperdicio(): string
    {
        if ($this->peso_desperdicio === null) {
            return 'Sem peso';
        }

        return $this->percentualReal() <= (float) ($this->maximo_desperdicio ?? 0)
            ? 'Dentro da meta'
            : 'Atenção';
    }
}