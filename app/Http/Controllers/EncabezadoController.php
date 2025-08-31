<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EncabezadoService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class EncabezadoController extends Controller
{
    protected $encabezadoService;

    public function __construct(EncabezadoService $encabezadoService)
    {
        $this->encabezadoService = $encabezadoService;
    }

    /**
     * Registra un nuevo encabezado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $encabezado = $this->encabezadoService->createEncabezado($request->all());
            return response()->json([
                'message' => 'Encabezado creado exitosamente.',
                'data' => $encabezado
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error inesperado.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}