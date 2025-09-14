<?php

namespace App\Services;

use App\Repositories\RespuestaUsuarioRepository;
use App\Models\RespuestaUsuario;
use Illuminate\Support\Facades\DB;

class RespuestaUsuarioService
{
    protected RespuestaUsuarioRepository $respuestaUsuarioRepository;

    public function __construct(RespuestaUsuarioRepository $respuestaUsuarioRepository)
    {
        $this->respuestaUsuarioRepository = $respuestaUsuarioRepository;
    }

    /**
     * Save the user's response.
     *
     * @param array $aData
     * @return RespuestaUsuario
     */
    public function saveResponse(array $aData): array
    {
        return DB::transaction(function () use ($aData) {
            $respuestas = [];

            foreach ($aData as $data) {
                $formattedData = [
                    'id_pregunta' => $data['id_pregunta'],
                    'id_usuario' => $data['id_usuario'],
                    'respuesta_usuario' => $data['opcion'] . ': ' . $data['descripcion_opcion'],
                    'correcta' => $data['correcta'],
                ];

                $respuesta = $this->respuestaUsuarioRepository->create($formattedData);
                $respuestas[] = $respuesta;
            }

            return $respuestas;
        });
    }
}