<?php

namespace App\Services;

use App\Repositories\ModuloRepository;

class ModuloService
{
    protected $moduloRepository;

    public function __construct(ModuloRepository $moduloRepository)
    {
        $this->moduloRepository = $moduloRepository;
    }

    /**
     * Obtiene todos los modulos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllModulos()
    {
        return $this->moduloRepository->all();
    }

    /**
     * Crea un nuevo modulo.
     *
     * @param array $data
     * @return Modulo
     */
    public function createModulo(array $data)
    {
        return $this->moduloRepository->create($data);
    }
}
