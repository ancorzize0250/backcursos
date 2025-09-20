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
                    'respuesta_usuario' => $data['opcion'],
                    'correcta' => $data['correcta'],
                ];

                $respuesta = $this->respuestaUsuarioRepository->create($formattedData);
                $respuestas[] = $respuesta;
            }

            return $respuestas;
        });
    }

    public function eliminarHistorial($id_usuario, $id_convocatoria)
    {
        $deletedCount = $this->respuestaUsuarioRepository->deleteHistorial($id_usuario, $id_convocatoria);

        if ($deletedCount > 0) {
            return ['status' => 'success', 'message' => 'Se eliminó el histórico correctamente'];
        } else {
            return ['status' => 'error', 'message' => 'No se encontró historial para la convocatoria y usuario especificados'];
        }
    }
}