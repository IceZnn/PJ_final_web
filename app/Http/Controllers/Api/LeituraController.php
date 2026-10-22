<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeituraRequest;
use App\Models\Leitura;
use Illuminate\Http\JsonResponse;

class LeituraController extends Controller
{
    public function store(StoreLeituraRequest $request): JsonResponse
    {
        $leitura = Leitura::create($request->validated());

        return response()->json([
            'id' => $leitura->id,
            'status' => 'ok',
        ], 201);
    }
}
