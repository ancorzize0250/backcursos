<?php

namespace App\Repositories;

use App\Models\RespuestaUsuario;

class RespuestaUsuarioRepository 
{
    /**
     * @param array $data
     * @return RespuestaUsuario
     */
    public function create(array $data): RespuestaUsuario
    {
        return RespuestaUsuario::create($data);
    }

    public function getUltimaPreguntaRespondida(int $idUsuario, int $idConvocatoria): ?int
    {
        return RespuestaUsuario::ultimaPreguntaRespondida($idUsuario, $idConvocatoria)
            ->value('id_pregunta');
    }

    public function deleteHistorial($id_usuario, $id_convocatoria)
    {
        return RespuestaUsuario::where('id_usuario', $id_usuario)
            ->whereIn('id_pregunta', function($query) use ($id_convocatoria) {
                $query->select('r.id_pregunta')
                      ->from('respuesta_usuarios as r')
                      ->join('preguntas as p', 'p.id', '=', 'r.id_pregunta')
                      ->join('encabezados as e', 'e.id', '=', 'p.id_encabezado')
                      ->join('modulos as m', 'm.id', '=', 'e.id_modulo')
                      ->where('m.id_convocatoria', $id_convocatoria);
            })
            ->delete();
    }
}
