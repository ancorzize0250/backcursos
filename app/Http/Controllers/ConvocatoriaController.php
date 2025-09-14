<?php

namespace App\Http\Controllers;

use App\Services\ConvocatoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ConvocatoriaController extends Controller
{
    protected $convocatoriaService;

    public function __construct(ConvocatoriaService $convocatoriaService)
    {
        $this->convocatoriaService = $convocatoriaService;
    }

     /**
     * Lista todas las convocatorias, con opción de búsqueda.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $convocatoria = request()->query('convocatoria'); 
            $id_usuario = request()->query('id_usuario'); 

            $convocatorias = $this->convocatoriaService->list($convocatoria, $id_usuario);
            return response()->json($convocatorias, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al obtener las convocatorias.'], 500);
        }
    }

    /**
     * Registra una nueva convocatoria.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:255|unique:convocatorias,codigo',
            'nombre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $convocatoria = $this->convocatoriaService->createConvocatoria($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Convocatoria registrada exitosamente.',
            'convocatoria' => $convocatoria,
        ], 201);
    }
}