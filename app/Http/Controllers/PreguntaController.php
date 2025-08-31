<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PreguntaService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class PreguntaController extends Controller
{
    protected $preguntaService;

    public function __construct(PreguntaService $preguntaService)
    {
        $this->preguntaService = $preguntaService;
    }

    /**
     * Registra una nueva pregunta.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $pregunta = $this->preguntaService->createPregunta($request->all());
            return response()->json([
                'message' => 'Pregunta creada exitosamente.',
                'data' => $pregunta
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

      /**
     * Obtiene las preguntas para un módulo y usuario específicos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $preguntas = $this->preguntaService->getFormattedPreguntas($request->all());
            return response()->json([
                'message' => 'Preguntas obtenidas exitosamente.',
                'data' => $preguntas
            ], 200);
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