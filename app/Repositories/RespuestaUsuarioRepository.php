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
}
