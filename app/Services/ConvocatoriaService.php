<?php

namespace App\Services;

use App\Repositories\ConvocatoriaRepository;

class ConvocatoriaService
{
    protected $convocatoriaRepository;

    public function __construct(ConvocatoriaRepository $convocatoriaRepository)
    {
        $this->convocatoriaRepository = $convocatoriaRepository;
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
    public function list(string $query = '')
    {
        return $this->convocatoriaRepository->list($query);
    }
}
