<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IndicadorDesperdicioSemanal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LedStatusController extends Controller
{
    public function show(Request $request, IndicadorDesperdicioSemanal $indicador): JsonResponse
    {
        $usuarioId = $request->attributes->get('usuario');

        return response()->json($indicador->paraUsuario((int) $usuarioId));
    }
}
