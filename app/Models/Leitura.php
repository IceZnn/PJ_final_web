<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leitura extends Model
{
    protected $fillable = [
        'device_id',
        'cell_1',
        'cell_2',
        'cell_3',
        'cell_4',
        'total',
    ];

    protected $casts = [
        'cell_1' => 'float',
        'cell_2' => 'float',
        'cell_3' => 'float',
        'cell_4' => 'float',
        'total' => 'float',
    ];
}
