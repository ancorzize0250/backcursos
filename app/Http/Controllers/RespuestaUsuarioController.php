<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RespuestaUsuarioService;
use Illuminate\Support\Facades\Validator;

class RespuestaUsuarioController extends Controller
{
    protected RespuestaUsuarioService $respuestaUsuarioService;

    public function __construct(RespuestaUsuarioService $respuestaUsuarioService)
    {
        $this->respuestaUsuarioService = $respuestaUsuarioService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validación de los datos del JSON
        $validator = Validator::make($request->all(), [
            '*.id_pregunta' => 'required|integer',
            '*.id_usuario' => 'required|integer',
            '*.opcion' => 'required|string|max:255',
            '*.descripcion_opcion' => 'required|string|max:500',
            '*.correcta' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $respuesta = $this->respuestaUsuarioService->saveResponse($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Respuesta de usuario guardada con éxito',
                'data' => $respuesta
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al guardar la respuesta del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
