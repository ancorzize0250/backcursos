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
     * Obtiene las preguntas y respuestas organizadas por encabezado.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getQuestions(Request $request): JsonResponse
    {
       $request->validate([
            'id_convocatoria'   => 'required',
            'id_usuario'        => 'required',
            'id_ultima_pregunta'=> 'required',
        ]);

        $convocatoriaId   = $request->query('id_convocatoria');
        $userId           = $request->query('id_usuario');
        $id_ultima_pregunta = $request->query('id_ultima_pregunta');

        $result = $this->preguntaService->getPreguntasByConvocatoria(
            $convocatoriaId, 
            $userId, 
            null, 
            $id_ultima_pregunta
        );

        return $result;
    }

    /**
     * Registra masivamente un conjunto de preguntas, encabezados y opciones.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeBulk(Request $request): JsonResponse
    {
        $data = $request->all();
        $result = $this->preguntaService->createBulk($data);

        return response()->json($result['message'], $result['status']);
    }
}