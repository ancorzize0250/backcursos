<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpcionService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class OpcionController extends Controller
{
    protected $opcionService;

    public function __construct(OpcionService $opcionService)
    {
        $this->opcionService = $opcionService;
    }

    /**
     * Registra múltiples opciones.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $opciones = $this->opcionService->createOpciones($request->all());
            return response()->json([
                'message' => 'Opciones creadas exitosamente.',
                'data' => $opciones
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