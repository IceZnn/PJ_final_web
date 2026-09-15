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
        'observacoes',
    ];

    protected $casts = [
        'salas' => 'array',
        'quantidade_preparada' => 'decimal:2',
        'maximo_desperdicio' => 'integer',
    ];
}