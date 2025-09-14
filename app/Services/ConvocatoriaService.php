<?php

namespace App\Services;

use App\Repositories\ConvocatoriaRepository;
use App\Repositories\RespuestaUsuarioRepository;

class ConvocatoriaService
{
    protected $convocatoriaRepository;
    protected $respuestaUsuarioRepository;

    public function __construct(ConvocatoriaRepository $convocatoriaRepository, RespuestaUsuarioRepository $respuestaUsuarioRepository)
    {
        $this->convocatoriaRepository = $convocatoriaRepository;
        $this->respuestaUsuarioRepository = $respuestaUsuarioRepository;
    }

    /**
     * Obtiene todas las convocatorias.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllConvocatorias()
    {
        return $this->convocatoriaRepository->all();
    }

    /**
     * Crea una nueva convocatoria.
     *
     * @param array $data
     * @return Convocatoria
     */
    public function createConvocatoria(array $data)
    {
        return $this->convocatoriaRepository->create($data);
    }

     /**
     * Lista todas las convocatorias, con opción de búsqueda por nombre.
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(string $query = '', $id_usuario)
    {
        $aList = $this->convocatoriaRepository->list($query);
        foreach($aList as $list)
        {
            $ultima_pregunta = $this->respuestaUsuarioRepository->getUltimaPreguntaRespondida($id_usuario, $list->id);
            $list->ultima_pregunta = $ultima_pregunta;
        }
        return $aList;
    }
}
